<?php

namespace App\Controller;

use App\Exception\RequestDataException;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("get-metamask-login-message", name="get-metamask-login-message", methods={"POST"})
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
        $address = $request->request->get('address');
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
     * @return RedirectResponse
     */
    public function googleRedirect(
        Request $request,
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $em,
        UserService $userService
    ): RedirectResponse {
        if ($request->get('code')) {
            [$user, $token] = $userService->googleLogin($request->get('code'));
            $em->flush();
        }
        //ToDo: передавать в редиректе токен
        return $this->redirect($parameterBag->get('frontDomain'));
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
     * @Route("get-blockchain-config", name="get-blockchain-config", methods={"GET"})
     *
     * @param ParameterBagInterface $parameterBag
     * @return JsonResponse
     */
    public function getBlockchainConfig(ParameterBagInterface $parameterBag)
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
     * @Route("get-metamask-login-message", methods={"OPTIONS"})
     * @Route("metamask-login", methods={"OPTIONS"})
     * @Route("get-google-login-url", methods={"OPTIONS"})
     * @Route("get-user-data", methods={"OPTIONS"})
     * @Route("service-login", methods={"OPTIONS"})
     * @Route("get-service-login-redirect", methods={"OPTIONS"})
     * @Route("get-blockchain-config", methods={"OPTIONS"})
     */
    public function options(): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST');
        return $response;
    }
}