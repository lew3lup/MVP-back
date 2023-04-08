<?php

namespace App\Entity;

use DateTimeImmutable;
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
class QuestTask extends Descriptionable
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
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active = true;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="added_at")
     */
    private $addedAt;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="deleted_at", nullable=true)
     */
    private $deletedAt;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return QuestTask
     */
    public function setActive(bool $active): QuestTask
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return QuestTask
     */
    public function delete(): QuestTask
    {
        if (!$this->deleted) {
            $this->deleted = true;
            $this->deletedAt = new DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getAddedAt(): DateTimeImmutable
    {
        return $this->addedAt;
    }

    /**
     * @param DateTimeImmutable $addedAt
     * @return QuestTask
     */
    public function setAddedAt(DateTimeImmutable $addedAt): QuestTask
    {
        $this->addedAt = $addedAt;
        return $this;
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

    /**
     * @return array
     */
    public function jsonSerializeDetailed(): array
    {
        return array_merge($this->jsonSerialize(), ['active' => $this->active]);
    }
}