<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность связи пользователя и ачивки
 *
 * @ORM\Table(name="users_achievements")
 * @ORM\Entity(repositoryClass="App\Repository\UserAchievementRepository")
 *
 * Class UserAchievement
 * @package App\Entity
 */
class UserAchievement
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userAchievements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var Achievement
     * @ORM\ManyToOne(targetEntity="Achievement", inversedBy="userAchievements")
     * @ORM\JoinColumn(name="achievement_id", referencedColumnName="id")
     */
    private $achievement;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserAchievement
     */
    public function setUser(User $user): UserAchievement
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Achievement
     */
    public function getAchievement(): Achievement
    {
        return $this->achievement;
    }

    /**
     * @param Achievement $achievement
     * @return UserAchievement
     */
    public function setAchievement(Achievement $achievement): UserAchievement
    {
        $this->achievement = $achievement;
        return $this;
    }
}