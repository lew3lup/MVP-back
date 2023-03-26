<?php

namespace App\Controller;

use App\Entity\User;
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
    /** @var UserService */
    private $userService;

    /**
     * GameAdminController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return User
     */
    private function getCurrentUser(Request $request): User
    {
        return $this->userService->getCurrentUser($request->headers->get('Authorization'));
    }

    /**
     * @Route("game/{gameId}", methods={"GET"})
     *
     * @param int $gameId
     * @param Request $request
     * @param GameService $gameService
     * @return JsonResponse
     */
    public function getGame(
        int $gameId,
        Request $request,
        GameService $gameService
    ): JsonResponse {
        $game = $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId());
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
     * @param GameService $gameService
     * @return JsonResponse
     */
    public function updateGame(
        int $gameId,
        Request $request,
        GameService $gameService
    ): JsonResponse {
        $game = $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId());
        //ToDo
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
    }

    public function addGameDescription(
        int $gameId,
        Request $request,
        GameService $gameService
    ): JsonResponse {
        $game = $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId());
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