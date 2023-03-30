<?php

namespace App\Controller;

use App\Entity\Quest;
use App\Entity\User;
use App\Service\GameService;
use App\Service\QuestService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function updateGame(
        int $gameId,
        Request $request,
        GameService $gameService,
        EntityManagerInterface $em
    ): JsonResponse {
        $game = $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId());
        //ToDo
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
    }

    public function addGameDescription(
        int $gameId,
        Request $request,
        GameService $gameService,
        EntityManagerInterface $em
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

    public function addQuest(
        int $gameId,
        Request $request,
        GameService $gameService,
        EntityManagerInterface $em
    ): JsonResponse {
        $game = $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId());
        //ToDo, вынести создание в сервис
        $quest = new Quest();
        $em->persist($quest);
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $quest->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("quest/{questId}", methods={"PUT"})
     *
     * @param int $questId
     * @param Request $request
     * @param QuestService $questService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function updateQuest(
        int $questId,
        Request $request,
        QuestService $questService,
        EntityManagerInterface $em
    ): JsonResponse {
        $quest = $questService->getByIdAndAdminId($questId, $this->getCurrentUser($request)->getId());
        //ToDo
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $quest->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("quest/{questId}", methods={"DELETE"})
     *
     * @param int $questId
     * @param Request $request
     * @param QuestService $questService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function removeQuest(
        int $questId,
        Request $request,
        QuestService $questService,
        EntityManagerInterface $em
    ): JsonResponse {
        $quest = $questService->getByIdAndAdminId($questId, $this->getCurrentUser($request)->getId());
        $quest->delete();
        $em->flush();
        return $this->json(['type' => 'success']);
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