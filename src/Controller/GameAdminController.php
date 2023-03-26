<?php

namespace App\Controller;

use App\Service\GameService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GameAdminController
 * @package App\Controller
 * @Route("game-admin/")
 */
class GameAdminController extends AbstractController
{
    /**
     * @Route("game/{gameId}", methods={"GET"})
     *
     * @param int $gameId
     * @param Request $request
     * @param UserService $userService
     * @param GameService $gameService
     * @return JsonResponse
     */
    public function getGame(
        int $gameId,
        Request $request,
        UserService $userService,
        GameService $gameService
    ): JsonResponse {
        $user = $userService->getCurrentUser($request->headers->get('Authorization'));
        $game = $gameService->getByIdAndAdminId($gameId, $user->getId());
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("game/{gameId}", methods={"PUT"})
     *
     * @param int $gameId
     * @param Request $request
     * @param UserService $userService
     * @param GameService $gameService
     * @return JsonResponse
     */
    public function updateGame(
        int $gameId,
        Request $request,
        UserService $userService,
        GameService $gameService
    ): JsonResponse {
        $user = $userService->getCurrentUser($request->headers->get('Authorization'));
        $game = $gameService->getByIdAndAdminId($gameId, $user->getId());
        //ToDo
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
    }

    public function addGameDescription(
        int $gameId,
        Request $request,
        UserService $userService,
        GameService $gameService
    ): JsonResponse {
        $user = $userService->getCurrentUser($request->headers->get('Authorization'));
        $game = $gameService->getByIdAndAdminId($gameId, $user->getId());
        //ToDo
    }

    public function updateGameDescription()
    {
        //ToDo
    }

    public function removeGameDescription()
    {
        //ToDo
    }

    public function addQuest()
    {
        //ToDo
    }

    public function updateQuest()
    {
        //ToDo
    }

    public function removeQuest()
    {
        //ToDo
    }

    public function addQuestDescription()
    {
        //ToDo
    }

    public function updateQuestDescription()
    {
        //ToDo
    }

    public function removeQuestDescription()
    {
        //ToDo
    }

    public function addQuestTask()
    {
        //ToDo
    }

    public function updateQuestTask()
    {
        //ToDo
    }

    public function removeQuestTask()
    {
        //ToDo
    }

    public function addQuestTaskDescription()
    {
        //ToDo
    }

    public function updateQuestTaskDescription()
    {
        //ToDo
    }

    public function removeQuestTaskDescription()
    {
        //ToDo
    }
}