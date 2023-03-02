<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность, отражающая данные о верификации пользователя во Fractal
 *
 * @ORM\Table(name="users_fractals")
 * @ORM\Entity(repositoryClass="App\Repository\UserFractalRepository")
 *
 * Class UserFractal
 * @package App\Entity
 */
class UserFractal
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userQuests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * @return UserFractal
     */
    public function setUser(User $user): UserFractal
    {
        $this->user = $user;
        return $this;
    }
}