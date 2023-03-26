<?php

namespace App\Service;

use App\Entity\Quest;
use App\Exception\NotFoundException;
use App\Repository\QuestRepository;

class QuestService
{
    /** @var QuestRepository */
    private $questRepo;

    /**
     * QuestService constructor.
     * @param QuestRepository $questRepo
     */
    public function __construct(QuestRepository $questRepo)
    {
        $this->questRepo = $questRepo;
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return Quest
     */
    public function getByIdAndAdminId(int $id, int $adminId): Quest
    {
        $quest = $this->questRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$quest) {
            throw new NotFoundException();
        }
        return $quest;
    }
}