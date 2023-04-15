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
class Achievement extends Descriptionable
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
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
            'name'          => $this->name,
            'description'   => $this->description,
        ];
    }
}