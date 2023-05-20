<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность венчурного фонда/инвестора
 *
 * @ORM\Table(name="backers")
 * @ORM\Entity(repositoryClass="App\Repository\BackerRepository")
 *
 * Class Backer
 * @package App\Entity
 */
class Backer extends Serializable
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
    private $name;
    /**
     * @var GameBacker[]
     * @ORM\OneToMany(targetEntity="GameBacker", mappedBy="backer")
     */
    private $gameBackers;

    /**
     * Backer constructor.
     */
    public function __construct()
    {
        $this->gameBackers = new ArrayCollection();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Backer
     */
    public function setName(string $name): Backer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection|GameBacker[]
     */
    public function getGameBackers(): Collection
    {
        return $this->gameBackers;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
        ];
    }
}