<?php

namespace App\Controller;

use App\Entity\UserFractal;
use App\Exception\ForbiddenException;
use App\Exception\RequestDataException;
use App\Repository\UserRepository;
use App\Service\FractalService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("get-metamask-login-message", name="get-metamask-login-message", methods={"GET"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getMetamaskLoginMessage(
        Request $request,
        EntityManagerInterface $em,
        UserService $userService
    ): JsonResponse {
        $address = $request->query->get('address');
        if (!$address) {
            throw new RequestDataException();
        }
        $loginMessage = $userService->getMetamaskLoginMessage($address);
        $em->flush();
        return $this->json([
            'type' => 'success',
            'loginMessage' => $loginMessage
        ]);
    }

    /**
     * @Route("metamask-login", name="metamask-login", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserService $userService
     * @return JsonResponse
     */
    public function metamaskLogin(
        Request $request,
        EntityManagerInterface $em,
        UserService $userService
    ): JsonResponse {
        $address = $request->request->get('address');
        $signature = $request->request->get('signature');
        if (!$address || !$signature) {
            throw new RequestDataException();
        }
        [$user, $token] = $userService->metamaskLogin($address, $signature);
        $em->flush();
        return $this->json([
            'type' => 'success',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * @Route("get-google-login-url", name="get-google-login-url", methods={"GET"})
     *
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getGoogleLoginUrl(UserService $userService): JsonResponse
    {
        return $this->json([
            'type' => 'success',
            'link' => $userService->getGoogleLoginUrl()
        ]);
    }

    /**
     * @Route("google-redirect", name="google-redirect", methods={"GET"})
     *
     * @param Request $request
     * @param ParameterBagInterface $parameterBag
     * @param EntityManagerInterface $em
     * @param UserService $userService
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function googleRedirect(
        Request $request,
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $em,
        UserService $userService,
        LoggerInterface $logger
    ): RedirectResponse {
        //ToDo: поменять адрес редиректа, когда он будет известен
        $url = $parameterBag->get('frontDomain');
        try {
            if ($request->get('code')) {
                [$user, $token] = $userService->googleLogin($request->get('code'));
                $em->flush();
                $url .= '?token=' . $token;
            }
        } catch (Exception $e) {
            $logger->warning($e->getMessage(), ['exception' => $e]);
        }
        return $this->redirect($url);
    }

    /**
     * @Route("get-user-data", name="get-user-data", methods={"GET"})
     *
     * @param Request $request
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getUserData(
        Request $request,
        UserService $userService
    ): JsonResponse {
        $user = $userService->getCurrentUser($request->headers->get('Authorization'));
        return $this->json([
            'type' => 'success',
            'data' => $user
        ]);
    }

    /**
     * @Route("link-metamask", name="link-metamask", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserService $userService
     * @return JsonResponse
     */
    public function linkMetamask(
        Request $request,
        EntityManagerInterface $em,
        UserService $userService
    ): JsonResponse {
        $user = $userService->getCurrentUser($request->headers->get('Authorization'));
        $address = $request->request->get('address');
        $signature = $request->request->get('signature');
        if (!$address || !$signature) {
            throw new RequestDataException();
        }
        $userService->linkMetamask($user, $address, $signature);
        $em->flush();
        return $this->json([
            'type' => 'success',
            'user' => $user
        ]);
    }

    /**
     * @Route("get-blockchain-config", name="get-blockchain-config", methods={"GET"})
     *
     * @param ParameterBagInterface $parameterBag
     * @return JsonResponse
     */
    public function getBlockchainConfig(ParameterBagInterface $parameterBag): JsonResponse
    {
        $blockchainConfigs = $parameterBag->get('blockchain');
        $data = [];
        foreach ($blockchainConfigs as $chainId => $config) {
            $chainData = [
                'chainId' => $chainId,
                'name' => $config['chainName'],
                'contractAddress' => $config['contractAddress'],
                'rpcUrl' => $config['rpcUrl'],
                'explorerUrl' => $config['explorerUrl'],
                'currencySymbol' => $config['currencySymbol'],
            ];
            $data[] = $chainData;
        }
        return $this->json([
            'type' => 'success',
            'data' => $data
        ]);
    }

    /**
     * @Route("get-verification-init-data", name="get-verification-init-data", methods={"GET"})
     *
     * @param Request $request
     * @param ParameterBagInterface $parameterBag
     * @param UserService $userService
     * @param FractalService $fractalService
     * @return JsonResponse
     */
    public function getVerificationInitData(
        Request $request,
        ParameterBagInterface $parameterBag,
        UserService $userService,
        FractalService $fractalService
    ): JsonResponse {
        $user = $userService->getCurrentUser($request->headers->get('Authorization'));
        if ($user->getUserFractal()) {
            throw new ForbiddenException();
        }
        $state = JWT::encode(['userId' => $user->getId()], $parameterBag->get('jwtSecretKey'), 'HS512');
        return $this->json([
            'type' => 'success',
            'data' => $fractalService->getAuthLink($state)
        ]);
    }

    /**
     * @Route("fractal-login", name="fractal-login", methods={"GET"})
     *
     * @param Request $request
     * @param FractalService $fractalService
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     * @param UserRepository $userRepo
     * @return RedirectResponse
     */
    public function fractalLogin(
        Request $request,
        FractalService $fractalService,
        EntityManagerInterface $em,
        ParameterBagInterface $parameterBag,
        LoggerInterface $logger,
        UserRepository $userRepo
    ): RedirectResponse {
        $code = $request->query->get('code');
        $state = $request->query->get('state');
        $error = $request->query->get('error');
        $errorDescription = $request->query->get('error_description');
        try {
            if ($code && $state) {
                $secretKey = $parameterBag->get('jwtSecretKey');
                $stateData = JWT::decode($state, new Key($secretKey, 'HS512'));
                if (empty($stateData->userId)) {
                    throw new Exception('incorrect state');
                }
                $user = $userRepo->findOneById($stateData->userId);
                if (!$user) {
                    throw new Exception('user with id=' . $stateData->userId . ' not found');
                }
                if ($user->getUserFractal()) {
                    throw new Exception('user with id=' . $stateData->userId . ' already verified');
                }
                $accessData = $fractalService->getAccessToken($code);
                $userInfo = $fractalService->getUserInfo($accessData['access_token']);
                $userFractal = (new UserFractal())
                    ->setUser($user)
                    ->setAccessData($accessData)
                    ->setUid($userInfo['uid'])
                    ->setVerificationCases($userInfo['verification_cases'])
                    ->setStatus(UserFractal::STATUSES[$userInfo['verification_cases'][0]['credential']])
                ;
                $em->persist($userFractal);
                $em->flush();
            } elseif ($error) {
                throw new Exception($error . ', Error description: ' . $errorDescription);
            }
        } catch (Exception $e) {
            $logger->error('Fractal login error: ' . $e->getMessage());
        }
        //ToDo: поменять адрес редиректа, когда он будет известен
        $redirectUri = $parameterBag->get('frontDomain');
        return $this->redirect($redirectUri);
    }

    /**
     * @Route("get-metamask-login-message", methods={"OPTIONS"})
     * @Route("metamask-login", methods={"OPTIONS"})
     * @Route("link-metamask", methods={"OPTIONS"})
     * @Route("get-google-login-url", methods={"OPTIONS"})
     * @Route("get-user-data", methods={"OPTIONS"})
     * @Route("service-login", methods={"OPTIONS"})
     * @Route("get-service-login-redirect", methods={"OPTIONS"})
     * @Route("get-blockchain-config", methods={"OPTIONS"})
     * @Route("get-verification-init-data", methods={"OPTIONS"})
     */
    public function options(): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST');
        return $response;
    }
}