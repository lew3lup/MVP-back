<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность квестового задания
 *
 * @ORM\Table(name="quests_tasks")
 * @ORM\Entity(repositoryClass="App\Repository\QuestTaskRepository")
 *
 * Class QuestTask
 * @package App\Entity
 */
class QuestTask extends SerializableEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var Quest
     * @ORM\ManyToOne(targetEntity="Quest", inversedBy="tasks")
     * @ORM\JoinColumn(name="quest_id", referencedColumnName="id")
     */
    private $quest;
    /**
     * @var QuestTaskDescription[]
     * @ORM\OneToMany(targetEntity="QuestTaskDescription", mappedBy="questTask")
     */
    private $descriptions;
    /**
     * @var UserQuestTask[]
     * @ORM\OneToMany(targetEntity="UserQuestTask", mappedBy="questTask")
     */
    private $userQuestTasks;

    /**
     * QuestTask constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
        $this->userQuestTasks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }

    /**
     * @param Quest $quest
     * @return QuestTask
     */
    public function setQuest(Quest $quest): QuestTask
    {
        $this->quest = $quest;
        return $this;
    }

    /**
     * @return Collection|QuestTaskDescription[]
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }

    /**
     * @return Collection|UserQuestTask[]
     */
    public function getUserQuestTasks(): Collection
    {
        return $this->userQuestTasks;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'descriptions'  => $this->descriptions->toArray(),
        ];
    }
}