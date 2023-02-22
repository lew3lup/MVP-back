<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\ForbiddenException;
use App\Exception\IncorrectEthAddressException;
use App\Exception\IncorrectSignatureException;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var UserRepository */
    private $userRepo;
    /** @var ParameterBagInterface */
    private $parameterBag;
    /** @var GethApiService */
    private $gethApiService;

    private $redis;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepo
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepo,
        ParameterBagInterface $parameterBag
    ) {
        $this->em = $em;
        $this->userRepo = $userRepo;
        $this->parameterBag = $parameterBag;
        $this->gethApiService = new GethApiService($parameterBag->get('gethAddress'));
        $this->redis = RedisAdapter::createConnection($parameterBag->get('redisSettings')['dsn']);
    }

    /**
     * @param string $address
     * @return User
     */
    public function getOrAddUser(string $address): User
    {
        $address = strtolower($address);
        if (!$this->checkEthAddress($address)) {
            throw new IncorrectEthAddressException();
        }
        $user = $this->userRepo->findOneByAddress($address);
        if (!$user) {
            $user = (new User())
                ->setAddress($address)
                ->setRegisteredAt(new DateTimeImmutable())
            ;
            $this->em->persist($user);
        }
        return $user;
    }

    /**
     * @param string $address
     * @return string
     */
    public function getLoginMessage(string $address): string
    {
        $user = $this->getOrAddUser($address);
        $message = $this->generateRandomString(
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            100
        );
        $this->redis->set($this->getMessageKey($user), $message, 300);
        return $message;
    }

    /**
     * @param string $address
     * @param string $signature
     * @return array
     */
    public function login(string $address, string $signature): array
    {
        $user = $this->getOrAddUser($address);
        //Получаем строку, которая давалась этому адресу на подпись, из кэша
        $messageKey = $this->getMessageKey($user);
        $message = $this->redis->get($messageKey);
        if (!$message) {
            throw new ForbiddenException();
        }
        //Извлекаем адрес из подписи
        try {
            $signerAddress = $this->gethApiService->recoverSignerAddress('0x' . bin2hex($message), $signature);
        } catch (Exception $e) {
            throw new ForbiddenException();
        }
        //Сверяем полученный адрес с имеющимся
        if (!$signerAddress || $signerAddress !== $user->getAddress()) {
            throw new ForbiddenException();
        }
        $this->redis->del($messageKey);
        return [$user, $this->getAuthorizationToken($user)];
    }

    /**
     * @param string $message
     * @param string $signature
     * @return array
     */
    public function serviceLogin(string $message, string $signature): array
    {
        //Извлекаем адрес из подписи
        try {
            $address = $this->gethApiService->recoverSignerAddress('0x' . bin2hex($message), $signature);
        } catch (Exception $e) {
            throw new ForbiddenException();
        }
        //Сверяем полученный адрес с имеющимся
        if (!$address) {
            throw new ForbiddenException();
        }
        $user = $this->getOrAddUser($address);
        return [$user, $this->getAuthorizationToken($user)];
    }

    /**
     * @param string $message
     * @param string $signature
     * @return User
     * @throws Exception
     */
    public function getUserFromSignature(string $message, string $signature): User
    {
        try {
            $signerAddress = $this->gethApiService->recoverSignerAddress('0x' . bin2hex($message), $signature);
            $user = $this->userRepo->findOneByAddress($signerAddress);
            if (!$user) {
                throw new Exception();
            }
            return $user;
        } catch (Exception $e) {
            throw new IncorrectSignatureException();
        }
    }

    /**
     * @param string|null $authorizationHeader
     * @return User
     */
    public function getCurrentUser(?string $authorizationHeader): User
    {
        if (!$authorizationHeader || strpos($authorizationHeader, 'Bearer ') !== 0) {
            throw new ForbiddenException();
        }
        $token = str_replace('Bearer ', '', $authorizationHeader);
        $secretKey = $this->parameterBag->get('jwtSecretKey');
        try {
            $data = JWT::decode($token, new Key($secretKey, 'HS512'));
        } catch (Exception $e) {
            throw new ForbiddenException('Token expired');
        }
        $now = new DateTimeImmutable();
        if ($data->nbf > $now->getTimestamp() || $data->exp < $now->getTimestamp()) {
            throw new ForbiddenException('Token expired');
        }
        $user = $this->userRepo->findOneByAddress($data->userAddress);
        if (!$user) {
            throw new ForbiddenException();
        }
        return $user;
    }

    /**
     * @param string $chars
     * @param int $length
     * @return string
     */
    private function generateRandomString(string $chars, int $length): string
    {
        $charsNum = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[mt_rand(0, $charsNum - 1)];
        }
        return $string;
    }

    /**
     * @param User $user
     * @return string
     */
    private function getAuthorizationToken(User $user): string
    {
        $secretKey = $this->parameterBag->get('jwtSecretKey');
        $issuedAt = new DateTimeImmutable();
        $expireAt = $issuedAt->modify('+60 minutes');
        $data = [
            'iat' => $issuedAt->getTimestamp(),
            'nbf' => $issuedAt->getTimestamp(),
            'exp' => $expireAt->getTimestamp(),
            'userAddress' => $user->getAddress()
        ];
        return JWT::encode($data, $secretKey, 'HS512');
    }

    /**
     * @param string $address
     * @return bool
     */
    private function checkEthAddress(string $address): bool
    {
        return preg_match('/^0x[0-9a-fA-F]{40}$/', $address);
    }

    /**
     * @param User $user
     * @return string
     */
    private function getMessageKey(User $user): string
    {
        return 'user' . $user->getId() . 'message';
    }
}