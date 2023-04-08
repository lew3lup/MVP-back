<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Quest;
use App\Exception\NotFoundException;
use App\Exception\RequestDataException;
use App\Repository\QuestRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class QuestService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var QuestRepository */
    private $questRepo;

    /**
     * QuestService constructor.
     * @param EntityManagerInterface $em
     * @param QuestRepository $questRepo
     */
    public function __construct(EntityManagerInterface $em, QuestRepository $questRepo)
    {
        $this->em = $em;
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

    /**
     * @param Game $game
     * @param array $data
     * @return Quest
     */
    public function addQuest(Game $game, array $data): Quest
    {
        //ToDo: проверка type на допустимость
        if (empty($data['type'])) {
            throw new RequestDataException();
        }
        $quest = (new Quest())->setGame($game)->setType($data['type'])->setAddedAt(new DateTimeImmutable());
        $this->em->persist($quest);
        return $quest;
    }

    /**
     * @param Quest $quest
     * @param array $data
     * @return Quest
     */
    public function updateQuest(Quest $quest, array $data): Quest
    {
        //ToDo: проверка type на допустимость
        if (empty($data['type']) || !isset($data['active'])) {
            throw new RequestDataException();
        }
        return $quest->setType($data['type'])->setActive($data['active']);
    }
}