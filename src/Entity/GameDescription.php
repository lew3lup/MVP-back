<?php

namespace App\Entity;

/**
 * Сущность описания игры/проекта на одном из языков
 *
 * @ORM\Table(name="games_descriptions")
 * @ORM\Entity(repositoryClass="App\Repository\GameDescriptionRepository")
 *
 * Class GameDescription
 * @package App\Entity
 */
class GameDescription extends Description
{
    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="descriptions")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     * @return GameDescription
     */
    public function setGame(Game $game): GameDescription
    {
        $this->game = $game;
        return $this;
    }
}