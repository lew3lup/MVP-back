<?php

namespace App\Entity;

/**
 * Сущность блокчейна
 *
 * @ORM\Table(name="chains")
 * @ORM\Entity(repositoryClass="App\Repository\ChainRepository")
 *
 * Class Chain
 * @package App\Entity
 */
class Chain extends Serializable
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
     * @var string
     * @ORM\Column(type="text", name="short_name", nullable=true)
     */
    private $shortName;

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
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName ?? $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'shortName' => $this->shortName,
        ];
    }
}