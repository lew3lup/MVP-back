<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность ачивки
 *
 * @ORM\Table(name="achievements")
 * @ORM\Entity(repositoryClass="App\Repository\AchievementRepository")
 *
 * Class Achievement
 * @package App\Entity
 */
class Achievement extends SerializableEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var AchievementDescription[]
     * @ORM\OneToMany(targetEntity="AchievementDescription", mappedBy="achievement")
     */
    private $descriptions;
    /**
     * @var UserAchievement[]
     * @ORM\OneToMany(targetEntity="UserAchievement", mappedBy="achievement")
     */
    private $userAchievements;

    /**
     * Achievement constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
        $this->userAchievements = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|AchievementDescription[]
     */
    public function getDescriptions(): Collection
    {
        return $this->descriptions;
    }

    /**
     * @return Collection|UserAchievement[]
     */
    public function getUserAchievements(): Collection
    {
        return $this->userAchievements;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'descriptions'  => $this->descriptions->toArray(),
        ];
    }
}