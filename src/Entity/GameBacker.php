<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность связи игры и инвестора
 *
 * @ORM\Table(name="games_backers")
 * @ORM\Entity(repositoryClass="App\Repository\GameBackerRepository")
 *
 * Class GameBacker
 * @package App\Entity
 */
class GameBacker
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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="gameDescriptions")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;
    /**
     * @var Backer
     * @ORM\ManyToOne(targetEntity="Backer", inversedBy="gameBackers")
     * @ORM\JoinColumn(name="backer_id", referencedColumnName="id")
     */
    private $backer;

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
     * @return GameBacker
     */
    public function setGame(Game $game): GameBacker
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return Backer
     */
    public function getBacker(): Backer
    {
        return $this->backer;
    }

    /**
     * @param Backer $backer
     * @return GameBacker
     */
    public function setBacker(Backer $backer): GameBacker
    {
        $this->backer = $backer;
        return $this;
    }
}