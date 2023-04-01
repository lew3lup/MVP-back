<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\DescriptionService;
use App\Service\GameService;
use App\Service\QuestService;
use App\Service\QuestTaskService;
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
        $game = $gameService->updateGame(
            $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId()),
            $request->get('url'),
            $request->get('active')
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("game/{gameId}/add-description", methods={"POST"})
     *
     * @param int $gameId
     * @param Request $request
     * @param GameService $gameService
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addGameDescription(
        int $gameId,
        Request $request,
        GameService $gameService,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $description = $descriptionService->addGameDescription(
            $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId()),
            $request->get('lang'),
            $request->get('name'),
            $request->get('description')
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    public function updateGameDescription()
    {
        //ToDo
    }

    public function removeGameDescription()
    {
        //ToDo
    }

    /**
     * @Route("game/{gameId}/add-quest", methods={"POST"})
     *
     * @param int $gameId
     * @param Request $request
     * @param GameService $gameService
     * @param QuestService $questService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addQuest(
        int $gameId,
        Request $request,
        GameService $gameService,
        QuestService $questService,
        EntityManagerInterface $em
    ): JsonResponse {
        $quest = $questService->addQuest(
            $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId()),
            $request->get('type')
        );
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
        $quest = $questService->updateQuest(
            $questService->getByIdAndAdminId($questId, $this->getCurrentUser($request)->getId()),
            $request->get('type'),
            $request->get('active')
        );
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
        $questService->getByIdAndAdminId($questId, $this->getCurrentUser($request)->getId())->delete();
        $em->flush();
        return $this->json(['type' => 'success']);
    }

    /**
     * @Route("quest/{questId}/add-description", methods={"POST"})
     *
     * @param int $questId
     * @param Request $request
     * @param QuestService $questService
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addQuestDescription(
        int $questId,
        Request $request,
        QuestService $questService,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $description = $descriptionService->addQuestDescription(
            $questService->getByIdAndAdminId($questId, $this->getCurrentUser($request)->getId()),
            $request->get('lang'),
            $request->get('name'),
            $request->get('description')
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("quest-description/{questDescriptionId}", methods={"PUT"})
     *
     * @param int $questDescriptionId
     * @param Request $request
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function updateQuestDescription(
        int $questDescriptionId,
        Request $request,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $description = $descriptionService->updateDescription(
            $descriptionService->getQuestDescriptionByIdAndAdminId(
                $questDescriptionId,
                $this->getCurrentUser($request)->getId()
            ),
            $request->get('type'),
            $request->get('active')
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("quest-description/{questDescriptionId}", methods={"DELETE"})
     *
     * @param int $questDescriptionId
     * @param Request $request
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function removeQuestDescription(
        int $questDescriptionId,
        Request $request,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->remove($descriptionService->getQuestDescriptionByIdAndAdminId(
            $questDescriptionId,
            $this->getCurrentUser($request)->getId()
        ));
        $em->flush();
        return $this->json(['type' => 'success']);
    }

    /**
     * @Route("quest/{questId}/add-task", methods={"POST"})
     *
     * @param int $questId
     * @param Request $request
     * @param QuestService $questService
     * @param QuestTaskService $questTaskService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addQuestTask(
        int $questId,
        Request $request,
        QuestService $questService,
        QuestTaskService $questTaskService,
        EntityManagerInterface $em
    ): JsonResponse {
        $questTask = $questTaskService->addQuestTask(
            $questService->getByIdAndAdminId($questId, $this->getCurrentUser($request)->getId())
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $questTask->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("task/{questTaskId}", methods={"PUT"})
     *
     * @param int $questTaskId
     * @param Request $request
     * @param QuestTaskService $questTaskService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function updateQuestTask(
        int $questTaskId,
        Request $request,
        QuestTaskService $questTaskService,
        EntityManagerInterface $em
    ): JsonResponse {
        $questTask = $questTaskService->updateQuestTask(
            $questTaskService->getByIdAndAdminId($questTaskId, $this->getCurrentUser($request)->getId()),
            $request->get('active')
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $questTask->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("task/{questTaskId}", methods={"DELETE"})
     *
     * @param int $questTaskId
     * @param Request $request
     * @param QuestTaskService $questTaskService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function removeQuestTask(
        int $questTaskId,
        Request $request,
        QuestTaskService $questTaskService,
        EntityManagerInterface $em
    ): JsonResponse {
        $questTaskService->getByIdAndAdminId($questTaskId, $this->getCurrentUser($request)->getId())->delete();
        $em->flush();
        return $this->json(['type' => 'success']);
    }

    /**
     * @Route("task/{questTaskId}/add-description", methods={"POST"})
     *
     * @param int $questTaskId
     * @param Request $request
     * @param QuestTaskService $questTaskService
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addQuestTaskDescription(
        int $questTaskId,
        Request $request,
        QuestTaskService $questTaskService,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $description = $descriptionService->addQuestTaskDescription(
            $questTaskService->getByIdAndAdminId($questTaskId, $this->getCurrentUser($request)->getId()),
            $request->get('lang'),
            $request->get('name'),
            $request->get('description')
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
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