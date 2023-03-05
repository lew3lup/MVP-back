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
    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_REJECTED = -1;

    public const STATUSES = [
        'pending' => self::STATUS_PENDING,
        'approved' => self::STATUS_APPROVED,
        'rejected' => self::STATUS_REJECTED,
    ];

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
     * @var array
     * @ORM\Column(type="json", name="access_data")
     */
    private $accessData;
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $uid;
    /**
     * @var array
     * @ORM\Column(type="json", name="verification_cases")
     */
    private $verificationCases;
    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status;

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
     * @return array
     */
    public function getAccessData(): array
    {
        return $this->accessData;
    }

    /**
     * @param array $accessData
     * @return UserFractal
     */
    public function setAccessData(array $accessData): UserFractal
    {
        $this->accessData = $accessData;
        return $this;
    }

    /**
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return UserFractal
     */
    public function setUid(string $uid): UserFractal
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return array
     */
    public function getVerificationCases(): array
    {
        return $this->verificationCases;
    }

    /**
     * @param array $verificationCases
     * @return UserFractal
     */
    public function setVerificationCases(array $verificationCases): UserFractal
    {
        $this->verificationCases = $verificationCases;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return UserFractal
     */
    public function setStatus(int $status): UserFractal
    {
        $this->status = $status;
        return $this;
    }
}