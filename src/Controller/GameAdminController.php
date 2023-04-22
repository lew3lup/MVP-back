<?php

namespace App\Controller;

use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Service\GameService;
use App\Service\QuestService;
use App\Service\QuestTaskService;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
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
     * @Route("add-game", methods={"POST"})
     *
     * @param Request $request
     * @param GameService $gameService
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function addGame(
        Request $request,
        GameService $gameService,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->beginTransaction();
        try {
            $game = $gameService->addGame($this->getCurrentUser($request), $this->getRequestData($request));
            $em->flush();
            $em->commit();
        } catch (DomainException $e) {
            $em->rollback();
            throw $e;
        } catch (Exception $e) {
            $em->rollback();
            throw new ConflictException('PATH_IS_ALREADY_IN_USE');
        }
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
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
            $this->getRequestData($request)
        );
        try {
            $em->flush();
        } catch (Exception $e) {
            throw new ConflictException('PATH_IS_ALREADY_IN_USE');
        }
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
    }

    public function setGameLogo(
        int $gameId,
        Request $request,
        GameService $gameService,
        EntityManagerInterface $em
    ): JsonResponse {
        $game = $gameService->setGameLogo(
            $gameService->getByIdAndAdminId($gameId, $this->getCurrentUser($request)->getId()),
            ''//ToDo
        );
        $em->flush();
        return $this->json([
            'type' => 'success',
            'data' => $game->jsonSerializeDetailed()
        ]);
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
            $this->getRequestData($request)
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
            $this->getRequestData($request)
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
            $this->getRequestData($request)
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
     * @Route("add-game", methods={"OPTIONS"})
     * @Route("game/{gameId}", methods={"OPTIONS"})
     * @Route("game/{gameId}/add-quest", methods={"OPTIONS"})
     * @Route("quest/{questId}", methods={"OPTIONS"})
     * @Route("quest/{questId}/add-task", methods={"OPTIONS"})
     * @Route("task/{questTaskId}", methods={"OPTIONS"})
     */
    public function options(): Response
    {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PUT, DELETE');
        return $response;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getRequestData(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            throw new BadRequestException();
        }
        return $data;
    }
}