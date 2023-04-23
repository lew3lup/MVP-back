<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность связи игры и блокчейна
 *
 * @ORM\Table(name="games_chains")
 * @ORM\Entity(repositoryClass="App\Repository\GameChainRepository")
 *
 * Class GameChain
 * @package App\Entity
 */
class GameChain
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
     * @var Chain
     * @ORM\ManyToOne(targetEntity="Chain", inversedBy="gameChains")
     * @ORM\JoinColumn(name="chain_id", referencedColumnName="id")
     */
    private $chain;

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
     * @return GameChain
     */
    public function setGame(Game $game): GameChain
    {
        $this->game = $game;
        $game->addGameChain($this);
        return $this;
    }

    /**
     * @return Chain
     */
    public function getChain(): Chain
    {
        return $this->chain;
    }

    /**
     * @param Chain $chain
     * @return GameChain
     */
    public function setChain(Chain $chain): GameChain
    {
        $this->chain = $chain;
        return $this;
    }
}