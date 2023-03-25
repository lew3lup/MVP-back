<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность игры/проекта
 *
 * @ORM\Table(name="games")
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 *
 * Class Game
 * @package App\Entity
 */
class Game extends SerializableEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $url;
    /**
     * @var GameDescription[]
     * @ORM\OneToMany(targetEntity="GameDescription", mappedBy="game")
     */
    private $descriptions;
    /**
     * @var Quest[]
     * @ORM\OneToMany(targetEntity="Quest", mappedBy="game")
     */
    private $quests;
    /**
     * @var GameAdmin[]
     * @ORM\OneToMany(targetEntity="GameAdmin", mappedBy="game")
     */
    private $admins;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
        $this->quests = new ArrayCollection();
        $this->admins = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Game
     */
    public function setUrl(string $url): Game
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return Collection|GameDescription[]
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }

    /**
     * @return Collection|Quest[]
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    /**
     * @return Collection|GameAdmin[]
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }
}