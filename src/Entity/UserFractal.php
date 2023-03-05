<?php

namespace App\Entity;

use DateTimeImmutable;
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
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;

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

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     * @return UserFractal
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): UserFractal
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}