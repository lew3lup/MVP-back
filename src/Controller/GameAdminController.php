<?php

namespace App\Controller;

use App\Exception\ConflictException;
use App\Service\DescriptionService;
use App\Service\GameService;
use App\Service\QuestService;
use App\Service\QuestTaskService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GameAdminController
 * @package App\Controller
 * @Route("game-admin/")
 */
class GameAdminController extends ApiController
{
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
            json_decode($request->getContent(), true)
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
            json_decode($request->getContent(), true)
        );
        try {
            $em->flush();
        } catch (Exception $e) {
            throw new ConflictException();
        }
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("game-description/{gameDescriptionId}", methods={"PUT"})
     *
     * @param int $gameDescriptionId
     * @param Request $request
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function updateGameDescription(
        int $gameDescriptionId,
        Request $request,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $description = $descriptionService->updateDescription(
            $descriptionService->getGameDescriptionByIdAndAdminId(
                $gameDescriptionId,
                $this->getCurrentUser($request)->getId()
            ),
            json_decode($request->getContent(), true)
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("game-description/{gameDescriptionId}", methods={"DELETE"})
     *
     * @param int $gameDescriptionId
     * @param Request $request
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function removeGameDescription(
        int $gameDescriptionId,
        Request $request,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->remove($descriptionService->getGameDescriptionByIdAndAdminId(
            $gameDescriptionId,
            $this->getCurrentUser($request)->getId()
        ));
        $em->flush();
        return $this->json(['type' => 'success']);
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
            json_decode($request->getContent(), true)
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
            json_decode($request->getContent(), true)
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
            json_decode($request->getContent(), true)
        );
        try {
            $em->flush();
        } catch (Exception $e) {
            throw new ConflictException();
        }
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
            json_decode($request->getContent(), true)
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
            json_decode($request->getContent(), true)
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
            json_decode($request->getContent(), true)
        );
        try {
            $em->flush();
        } catch (Exception $e) {
            throw new ConflictException();
        }
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("task-description/{questTaskDescriptionId}", methods={"PUT"})
     *
     * @param int $questTaskDescriptionId
     * @param Request $request
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function updateQuestTaskDescription(
        int $questTaskDescriptionId,
        Request $request,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $description = $descriptionService->updateDescription(
            $descriptionService->getQuestTaskDescriptionByIdAndAdminId(
                $questTaskDescriptionId,
                $this->getCurrentUser($request)->getId()
            ),
            json_decode($request->getContent(), true)
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $description->jsonSerializeDetailed()
        ]);
    }

    /**
     * @Route("task-description/{questTaskDescriptionId}", methods={"DELETE"})
     *
     * @param int $questTaskDescriptionId
     * @param Request $request
     * @param DescriptionService $descriptionService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function removeQuestTaskDescription(
        int $questTaskDescriptionId,
        Request $request,
        DescriptionService $descriptionService,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->remove($descriptionService->getQuestTaskDescriptionByIdAndAdminId(
            $questTaskDescriptionId,
            $this->getCurrentUser($request)->getId()
        ));
        $em->flush();
        return $this->json(['type' => 'success']);
    }

    /**
     * @Route("game/{gameId}", methods={"OPTIONS"})
     * @Route("game/{gameId}/add-description", methods={"OPTIONS"})
     * @Route("game-description/{gameDescriptionId}", methods={"OPTIONS"})
     * @Route("game/{gameId}/add-quest", methods={"OPTIONS"})
     * @Route("quest/{questId}", methods={"OPTIONS"})
     * @Route("quest/{questId}/add-description", methods={"OPTIONS"})
     * @Route("quest-description/{questDescriptionId}", methods={"OPTIONS"})
     * @Route("quest/{questId}/add-task", methods={"OPTIONS"})
     * @Route("task/{questTaskId}", methods={"OPTIONS"})
     * @Route("task/{questTaskId}/add-description", methods={"OPTIONS"})
     * @Route("task-description/{questTaskDescriptionId}", methods={"OPTIONS"})
     */
    public function options(): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST');
        return $response;
    }
}