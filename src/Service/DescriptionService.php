<?php

namespace App\Service;

use App\Entity\Description;
use App\Entity\Game;
use App\Entity\GameDescription;
use App\Entity\Quest;
use App\Entity\QuestDescription;
use App\Entity\QuestTask;
use App\Entity\QuestTaskDescription;
use App\Exception\NotFoundException;
use App\Exception\BadRequestException;
use App\Repository\GameDescriptionRepository;
use App\Repository\QuestDescriptionRepository;
use App\Repository\QuestTaskDescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DescriptionService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var GameDescriptionRepository */
    private $gameDescriptionRepo;
    /** @var QuestDescriptionRepository */
    private $questDescriptionRepo;
    /** @var QuestTaskDescriptionRepository */
    private $questTaskDescriptionRepo;

    /**
     * DescriptionService constructor.
     * @param EntityManagerInterface $em
     * @param GameDescriptionRepository $gameDescriptionRepo
     * @param QuestDescriptionRepository $questDescriptionRepo
     * @param QuestTaskDescriptionRepository $questTaskDescriptionRepo
     */
    public function __construct(
        EntityManagerInterface $em,
        GameDescriptionRepository $gameDescriptionRepo,
        QuestDescriptionRepository $questDescriptionRepo,
        QuestTaskDescriptionRepository $questTaskDescriptionRepo
    ) {
        $this->em = $em;
        $this->gameDescriptionRepo = $gameDescriptionRepo;
        $this->questDescriptionRepo = $questDescriptionRepo;
        $this->questTaskDescriptionRepo = $questTaskDescriptionRepo;
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return GameDescription
     */
    public function getGameDescriptionByIdAndAdminId(int $id, int $adminId): GameDescription
    {
        $description = $this->gameDescriptionRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$description) {
            throw new NotFoundException();
        }
        return $description;
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return QuestDescription
     */
    public function getQuestDescriptionByIdAndAdminId(int $id, int $adminId): QuestDescription
    {
        $description = $this->questDescriptionRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$description) {
            throw new NotFoundException();
        }
        return $description;
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return QuestTaskDescription
     */
    public function getQuestTaskDescriptionByIdAndAdminId(int $id, int $adminId): QuestTaskDescription
    {
        $description = $this->questTaskDescriptionRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$description) {
            throw new NotFoundException();
        }
        return $description;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return GameDescription
     */
    public function addGameDescription(Game $game, array $data): GameDescription
    {
        /** @var GameDescription $description */
        $description = $this->addDescription((new GameDescription())->setGame($game), $data);
        return $description;
    }

    /**
     * @param Quest $quest
     * @param array $data
     * @return QuestDescription
     */
    public function addQuestDescription(Quest $quest, array $data): QuestDescription
    {
        /** @var QuestDescription $description */
        $description = $this->addDescription((new QuestDescription())->setQuest($quest), $data);
        return $description;
    }

    /**
     * @param QuestTask $questTask
     * @param array $data
     * @return QuestTaskDescription
     */
    public function addQuestTaskDescription(QuestTask $questTask, array $data): QuestTaskDescription
    {
        /** @var QuestTaskDescription $description */
        $description = $this->addDescription((new QuestTaskDescription())->setQuestTask($questTask), $data);
        return $description;
    }

    /**
     * @param Description $descriptionEntity
     * @param array $data
     * @return Description
     */
    public function updateDescription(Description $descriptionEntity, array $data): Description
    {
        if (empty($data['name']) || empty($data['description'])) {
            throw new BadRequestException();
        }
        return $descriptionEntity->setName($data['name'])->setDescription($data['description']);
    }

    /**
     * @param Description $descriptionEntity
     * @param array $data
     * @return Description
     */
    private function addDescription(Description $descriptionEntity, array $data): Description
    {
        if (empty($data['lang']) || empty($data['name']) || empty($data['description'])) {
            throw new BadRequestException();
        }
        $lang = strtolower($data['lang']);
        if (!in_array($lang, Description::LANGS)) {
            throw new BadRequestException('INVALID_LANGUAGE');
        }
        $descriptionEntity->setLang($lang)->setName($data['name'])->setDescription($data['description']);
        $this->em->persist($descriptionEntity);
        return $descriptionEntity;
    }
}