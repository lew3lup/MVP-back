<?php

namespace App\Entity;

/**
 * Сущность описания ачивки на одном из языков
 *
 * @ORM\Table(name="achievements_descriptions")
 * @ORM\Entity(repositoryClass="App\Repository\AchievementDescriptionRepository")
 *
 * Class AchievementDescription
 * @package App\Entity
 */
class AchievementDescription extends Description
{
    /**
     * @var Achievement
     * @ORM\ManyToOne(targetEntity="Achievement", inversedBy="descriptions")
     * @ORM\JoinColumn(name="achievement_id", referencedColumnName="id")
     */
    private $achievement;

    /**
     * @return Achievement
     */
    public function getAchievement(): Achievement
    {
        return $this->achievement;
    }

    /**
     * @param Achievement $achievement
     * @return AchievementDescription
     */
    public function setAchievement(Achievement $achievement): AchievementDescription
    {
        $this->achievement = $achievement;
        return $this;
    }
}