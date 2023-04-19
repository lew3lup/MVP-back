<?php

namespace App\Entity;

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