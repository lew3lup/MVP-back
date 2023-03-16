<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserFractal;
use App\Exception\ForbiddenException;
use App\Exception\IncorrectEthAddressException;
use App\Exception\IncorrectSignatureException;
use App\Exception\UnauthorizedException;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Google\Client;
use Google_Service_Oauth2;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    /** @var Client */
    private $googleClient;
    /** @var EntityManagerInterface */
    private $em;
    /** @var UserRepository */
    private $userRepo;
    /** @var ParameterBagInterface */
    private $parameterBag;
    /** @var LoggerInterface */
    private $logger;
    /** @var GethApiService */
    private $gethApiService;

    private $redis;

    /**
     * UserService constructor.
     * @param Client $googleClient
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepo
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     */
    public function __construct(
        Client $googleClient,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        ParameterBagInterface $parameterBag,
        LoggerInterface $logger
    ) {
        $this->googleClient = $googleClient;
        $this->em = $em;
        $this->userRepo = $userRepo;
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
        $this->gethApiService = new GethApiService($parameterBag->get('gethAddress'));
        $this->redis = RedisAdapter::createConnection($parameterBag->get('redisSettings')['dsn']);
    }

    /**
     * @param string $address
     * @return string
     */
    public function getMetamaskLoginMessage(string $address): string
    {
        $address = strtolower($address);
        $message = $this->generateRandomString(
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            100
        );
        $this->redis->set($this->getMessageKey($address), $message, 300);
        return $message;
    }

    /**
     * @param string $address
     * @param string $signature
     * @return array
     */
    public function metamaskLogin(string $address, string $signature): array
    {
        $address = strtolower($address);
        $this->checkSignature($address, $signature);
        $user = $this->getOrAddUserByAddress($address);
        return [$user, $this->getAuthorizationToken($user)];
    }

    /**
     * @param User $user
     * @param string $address
     * @param string $signature
     */
    public function linkMetamask(User $user, string $address, string $signature): void
    {
        if ($user->getAddress()) {
            throw new ForbiddenException();
        }
        $address = strtolower($address);
        $this->checkSignature($address, $signature);
        if ($this->userRepo->findOneByAddress($address)) {
            throw new ForbiddenException('Address already in use');
        }
        $user->setAddress($address);
    }

    /**
     * @return string
     */
    public function getGoogleLoginUrl(): string
    {
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        return $this->googleClient->createAuthUrl();
    }

    /**
     * @param string $code
     * @return array
     */
    public function googleLogin(string $code): array
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);
        $this->googleClient->setAccessToken($token['access_token']);
        $googleOauth = new Google_Service_Oauth2($this->googleClient);
        $googleAccountInfo = $googleOauth->userinfo->get();
        $email = $googleAccountInfo->email;
        $name = $googleAccountInfo->name;
        if (!$email) {
            throw new ForbiddenException();
        }
        $user = $this->getOrAddUserByEmail($email);
        if ($name && !$user->getName()) {
            $user->setName($name);
        }
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
        $user = $this->getOrAddUserByAddress($address);
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
            throw new UnauthorizedException();
        }
        $token = str_replace('Bearer ', '', $authorizationHeader);
        $secretKey = $this->parameterBag->get('jwtSecretKey');
        try {
            $data = JWT::decode($token, new Key($secretKey, 'HS512'));
        } catch (Exception $e) {
            throw new UnauthorizedException('Token expired');
        }
        $now = new DateTimeImmutable();
        if ($data->nbf > $now->getTimestamp() || $data->exp < $now->getTimestamp()) {
            throw new UnauthorizedException('Token expired');
        }
        $user = $this->userRepo->findOneById($data->userId);
        if (!$user) {
            throw new UnauthorizedException();
        }
        return $user;
    }

    public function generateLew3lupIdMintingSignature(User $user): string
    {
        if (!$user->isVerified()) {
            //ToDo: выбрасываем исключение
        }
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
            'userId' => $user->getId()
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
     * @param string $address
     * @return string
     */
    private function getMessageKey(string $address): string
    {
        return $address . 'message';
    }

    /**
     * @param string $address
     * @param string $signature
     */
    private function checkSignature(string $address, string $signature): void
    {
        //Получаем строку, которая давалась этому адресу на подпись, из кэша
        $messageKey = $this->getMessageKey($address);
        $message = $this->redis->get($messageKey);
        if (!$message) {
            throw new ForbiddenException();
        }
        //Извлекаем адрес из подписи
        try {
            $signerAddress = $this->gethApiService->recoverSignerAddress('0x' . bin2hex($message), $signature);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
            throw new ForbiddenException();
        }
        //Сверяем полученный адрес с имеющимся
        if (!$signerAddress || $signerAddress !== $address) {
            throw new ForbiddenException();
        }
        $this->redis->del($messageKey);
    }

    /**
     * @param string $address
     * @return User
     */
    private function getOrAddUserByAddress(string $address): User
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
     * @param string $email
     * @return User
     */
    private function getOrAddUserByEmail(string $email): User
    {
        $user = $this->userRepo->findOneByEmail($email);
        if (!$user) {
            $user = (new User())
                ->setEmail($email)
                ->setRegisteredAt(new DateTimeImmutable())
            ;
            $this->em->persist($user);
        }
        return $user;
    }
}