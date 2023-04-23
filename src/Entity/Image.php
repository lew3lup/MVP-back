<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность картинки
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 *
 * Class Image
 * @package App\Entity
 */
class Image extends Serializable
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
     * @var string
     * @ORM\Column(type="text")
     */
    private $url;

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
     * @return Image
     */
    public function setGame(Game $game): Image
    {
        $this->game = $game;
        return $this;
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
     * @return Image
     */
    public function setUrl(string $url): Image
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'    => $this->id,
            'url'   => $this->url,
        ];
    }
}