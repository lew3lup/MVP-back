<?php

namespace App\Service;

use App\Entity\Quest;
use App\Entity\QuestTask;
use App\Exception\NotFoundException;
use App\Exception\BadRequestException;
use App\Repository\QuestTaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class QuestTaskService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var QuestTaskRepository */
    private $questTaskRepo;

    /**
     * QuestTaskService constructor.
     * @param EntityManagerInterface $em
     * @param QuestTaskRepository $questTaskRepo
     */
    public function __construct(EntityManagerInterface $em, QuestTaskRepository $questTaskRepo)
    {
        $this->em = $em;
        $this->questTaskRepo = $questTaskRepo;
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return QuestTask
     */
    public function getByIdAndAdminId(int $id, int $adminId): QuestTask
    {
        $questTask = $this->questTaskRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$questTask) {
            throw new NotFoundException();
        }
        return $questTask;
    }

    /**
     * @param Quest $quest
     * @return QuestTask
     */
    public function addQuestTask(Quest $quest): QuestTask
    {
        $questTask = (new QuestTask())->setQuest($quest)->setAddedAt(new DateTimeImmutable());
        $this->em->persist($questTask);
        return $questTask;
    }

    /**
     * @param QuestTask $questTask
     * @param array $data
     * @return QuestTask
     */
    public function updateQuestTask(QuestTask $questTask, array $data): QuestTask
    {
        if (!isset($data['active'])) {
            throw new BadRequestException();
        }
        return $questTask->setActive($data['active']);
    }
}