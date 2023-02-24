<?php

namespace App\Entity;

/**
 * Сущность админа игры
 *
 * @ORM\Table(name="games_admins")
 * @ORM\Entity(repositoryClass="App\Repository\GameAdminRepository")
 *
 * Class GameAdmin
 * @package App\Entity
 */
class GameAdmin
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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="admins")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="adminedGames")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * @return GameAdmin
     */
    public function setGame(Game $game): GameAdmin
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return GameAdmin
     */
    public function setUser(User $user): GameAdmin
    {
        $this->user = $user;
        return $this;
    }
}