<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность, отражающая реферальные взаимосвязи
 *
 * @ORM\Table(name="users_referrals")
 * @ORM\Entity(repositoryClass="App\Repository\UserReferralRepository")
 *
 * Class UserReferral
 * @package App\Entity
 */
class UserReferral
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * Пользователь-партнёр
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="partners")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * Пользователь, которого привёл партнёр
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="referrals")
     * @ORM\JoinColumn(name="referral_id", referencedColumnName="id")
     */
    private $referral;

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
     * @return UserReferral
     */
    public function setUser(User $user): UserReferral
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getReferral(): User
    {
        return $this->referral;
    }

    /**
     * @param User $referral
     * @return UserReferral
     */
    public function setReferral(User $referral): UserReferral
    {
        $this->referral = $referral;
        return $this;
    }
}