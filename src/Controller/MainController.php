<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Exception\RequestDataException;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("get-login-message", name="get-login-message", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserService $userService
     * @return JsonResponse
     */
    public function getLoginMessage(
        Request $request,
        EntityManagerInterface $em,
        UserService $userService
    ): JsonResponse {
        $address = $request->request->get('address');
        if (!$address) {
            throw new RequestDataException();
        }
        $loginMessage = $userService->getLoginMessage($address);
        $em->flush();
        return $this->json([
            'type' => 'success',
            'loginMessage' => $loginMessage
        ]);
    }

    /**
     * @Route("login", name="login", methods={"POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserService $userService
     * @return JsonResponse
     */
    public function login(
        Request $request,
        EntityManagerInterface $em,
        UserService $userService
    ): JsonResponse {
        $address = $request->request->get('address');
        $signature = $request->request->get('signature');
        if (!$address || !$signature) {
            throw new RequestDataException();
        }
        [$user, $token] = $userService->login($address, $signature);
        $em->flush();
        return $this->json([
            'type' => 'success',
            'token' => $token,
            'user' => $user
        ]);
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
     * @Route("get-login-message", methods={"OPTIONS"})
     * @Route("login", methods={"OPTIONS"})
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