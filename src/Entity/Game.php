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
class Game extends Descriptionable
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * SEO URL на нашем сайте
     *
     * @var string
     * @ORM\Column(type="text")
     */
    private $path;
    /**
     * Сайт игры
     *
     * @var string
     * @ORM\Column(type="text", name="home_page")
     */
    private $homePage;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $twitter;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $discord;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $telegram;
    /**
     * @var string
     * @ORM\Column(type="text", name="coin_market_cap", nullable=true)
     */
    private $coinMarketCap;
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
     * @var GameCategory[]
     * @ORM\OneToMany(targetEntity="GameCategory", mappedBy="game")
     */
    private $gameCategories;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
        $this->quests = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->gameCategories = new ArrayCollection();
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
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Game
     */
    public function setPath(string $path): Game
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomePage(): string
    {
        return $this->homePage;
    }

    /**
     * @param string $homePage
     * @return Game
     */
    public function setHomePage(string $homePage): Game
    {
        $this->homePage = $homePage;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     * @return Game
     */
    public function setTwitter(?string $twitter): Game
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    /**
     * @param string $discord
     * @return Game
     */
    public function setDiscord(?string $discord): Game
    {
        $this->discord = $discord;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    /**
     * @param string $telegram
     * @return Game
     */
    public function setTelegram(?string $telegram): Game
    {
        $this->telegram = $telegram;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoinMarketCap(): string
    {
        return $this->coinMarketCap;
    }

    /**
     * @param string $coinMarketCap
     * @return Game
     */
    public function setCoinMarketCap(?string $coinMarketCap): Game
    {
        $this->coinMarketCap = $coinMarketCap;
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
     * @return Collection|GameCategory[]
     */
    public function getGameCategories(): Collection
    {
        return $this->gameCategories;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $categories = [];
        foreach ($this->gameCategories as $gameCategory) {
            $categories[] = $gameCategory->getCategory();
        }
        return [
            'id'            => $this->id,
            'path'          => $this->path,
            'homePage'      => $this->homePage,
            'twitter'       => $this->twitter,
            'discord'       => $this->discord,
            'telegram'      => $this->telegram,
            'active'        => $this->active,
            'descriptions'  => $this->descriptions->toArray(),
            'categories'    => $categories,
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