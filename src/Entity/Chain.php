<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var GameChain[]
     * @ORM\OneToMany(targetEntity="GameChain", mappedBy="chain")
     */
    private $gameChains;

    /**
     * Chain constructor.
     */
    public function __construct()
    {
        $this->gameChains = new ArrayCollection();
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
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName ?? $this->name;
    }

    /**
     * @return Collection|GameChain[]
     */
    public function getGameChains(): Collection
    {
        return $this->gameChains;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'shortName' => $this->getShortName(),
        ];
    }
}