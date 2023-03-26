<?php

namespace App\Service;

use App\Entity\QuestTask;
use App\Exception\NotFoundException;
use App\Repository\QuestTaskRepository;

class QuestTaskService
{
    /** @var QuestTaskRepository */
    private $questTaskRepo;

    /**
     * QuestTaskService constructor.
     * @param QuestTaskRepository $questTaskRepo
     */
    public function __construct(QuestTaskRepository $questTaskRepo)
    {
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
}