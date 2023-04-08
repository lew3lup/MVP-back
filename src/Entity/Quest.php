<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность квеста
 *
 * @ORM\Table(name="quests")
 * @ORM\Entity(repositoryClass="App\Repository\QuestRepository")
 *
 * Class Quest
 * @package App\Entity
 */
class Quest extends Descriptionable
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="quests")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;
    /**
     * Тип квеста
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    private $type;
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
     * @var QuestDescription[]
     * @ORM\OneToMany(targetEntity="QuestDescription", mappedBy="quest")
     */
    private $descriptions;
    /**
     * @var QuestTask[]
     * @ORM\OneToMany(targetEntity="QuestTask", mappedBy="quest")
     */
    private $tasks;
    /**
     * @var UserQuest[]
     * @ORM\OneToMany(targetEntity="UserQuest", mappedBy="quest")
     */
    private $userQuests;

    /**
     * Quest constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->userQuests = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     * @return Quest
     */
    public function setGame(Game $game): Quest
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return Quest
     */
    public function setType(int $type): Quest
    {
        $this->type = $type;
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
     * @return Quest
     */
    public function setActive(bool $active): Quest
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return Quest
     */
    public function delete(): Quest
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
     * @return Quest
     */
    public function setAddedAt(DateTimeImmutable $addedAt): Quest
    {
        $this->addedAt = $addedAt;
        return $this;
    }

    /**
     * @return Collection|UserQuest[]
     */
    public function getUserQuests(): Collection
    {
        return $this->userQuests;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'type'          => $this->type,
            'descriptions'  => $this->descriptions->toArray(),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerializeDetailed(): array
    {
        $tasks = [];
        foreach ($this->tasks as $task) {
            if (!$task->isDeleted()) {
                $tasks[] = $task->jsonSerializeDetailed();
            }
        }
        return array_merge($this->jsonSerialize(), [
            'active'    => $this->active,
            'tasks'     => $tasks,
        ]);
    }
}