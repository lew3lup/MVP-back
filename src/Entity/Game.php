<?php

namespace App\Entity;

use DateTimeImmutable;
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
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active = true;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="added_at")
     */
    private $addedAt;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="deleted_at", nullable=true)
     */
    private $deletedAt;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Game
     */
    public function setActive(bool $active): Game
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return Game
     */
    public function delete(): Game
    {
        if (!$this->deleted) {
            $this->deleted = true;
            $this->deletedAt = new DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getAddedAt(): DateTimeImmutable
    {
        return $this->addedAt;
    }

    /**
     * @param DateTimeImmutable $addedAt
     * @return Game
     */
    public function setAddedAt(DateTimeImmutable $addedAt): Game
    {
        $this->addedAt = $addedAt;
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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'url'           => $this->url,
            'active'        => $this->active,
            'descriptions'  => $this->descriptions->toArray(),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerializeDetailed(): array
    {
        $quests = [];
        foreach ($this->quests as $quest) {
            if (!$quest->isDeleted()) {
                $quests[] = $quest->jsonSerializeDetailed();
            }
        }
        return array_merge($this->jsonSerialize(), ['quests' => $quests]);
    }
}