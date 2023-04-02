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
use App\Exception\RequestDataException;
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
     * @param string $lang
     * @param string $name
     * @param string $description
     * @return GameDescription
     */
    public function addGameDescription(
        Game $game,
        string $lang,
        string $name,
        string $description
    ): GameDescription {
        /** @var GameDescription $description */
        $description = $this->addDescription(
            (new GameDescription())->setGame($game),
            $lang,
            $name,
            $description
        );
        return $description;
    }

    /**
     * @param Quest $quest
     * @param string $lang
     * @param string $name
     * @param string $description
     * @return QuestDescription
     */
    public function addQuestDescription(
        Quest $quest,
        string $lang,
        string $name,
        string $description
    ): QuestDescription {
        /** @var QuestDescription $description */
        $description = $this->addDescription(
            (new QuestDescription())->setQuest($quest),
            $lang,
            $name,
            $description
        );
        return $description;
    }

    /**
     * @param QuestTask $questTask
     * @param string $lang
     * @param string $name
     * @param string $description
     * @return QuestTaskDescription
     */
    public function addQuestTaskDescription(
        QuestTask $questTask,
        string $lang,
        string $name,
        string $description
    ): QuestTaskDescription {
        /** @var QuestTaskDescription $description */
        $description = $this->addDescription(
            (new QuestTaskDescription())->setQuestTask($questTask),
            $lang,
            $name,
            $description
        );
        return $description;
    }

    /**
     * @param Description $descriptionEntity
     * @param string $name
     * @param string $description
     * @return Description
     */
    public function updateDescription(
        Description $descriptionEntity,
        string $name,
        string $description
    ): Description {
        if (!$name || !$description) {
            throw new RequestDataException();
        }
        return $descriptionEntity->setName($name)->setDescription($description);
    }

    /**
     * @param Description $descriptionEntity
     * @param string $lang
     * @param string $name
     * @param string $description
     * @return Description
     */
    private function addDescription(
        Description $descriptionEntity,
        string $lang,
        string $name,
        string $description
    ): Description {
        $lang = strtolower($lang);
        if (!in_array($lang, Description::LANGS) || !$name || !$description) {
            throw new RequestDataException();
        }
        $descriptionEntity->setLang($lang)->setName($name)->setDescription($description);
        $this->em->persist($descriptionEntity);
        return $descriptionEntity;
    }
}