<?php

namespace App\Entity;

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
class Quest
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
     * Quest constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
        $this->tasks = new ArrayCollection();
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
     * @return Collection|QuestDescription[]
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }
}