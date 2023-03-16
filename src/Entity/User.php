<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Сущность пользователя
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * Class User
 * @package App\Entity
 */
class User implements JsonSerializable
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * ETH-адрес пользователя
     *
     * @var string
     * @ORM\Column(type="string", length=42, nullable=true)
     */
    private $address;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $name;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="registered_at")
     */
    private $registeredAt;
    /**
     * @var SbtToken[]
     * @ORM\OneToMany(targetEntity="SbtToken", mappedBy="user")
     */
    private $sbtTokens;
    /**
     * @var UserAchievement[]
     * @ORM\OneToMany(targetEntity="UserAchievement", mappedBy="user")
     */
    private $userAchievements;
    /**
     * @var UserQuest[]
     * @ORM\OneToMany(targetEntity="UserQuest", mappedBy="quest")
     */
    private $userQuests;
    /**
     * @var UserQuestTask[]
     * @ORM\OneToMany(targetEntity="UserQuestTask", mappedBy="questTask")
     */
    private $userQuestTasks;
    /**
     * @var UserReferral[]
     * @ORM\OneToMany(targetEntity="UserReferral", mappedBy="user")
     */
    private $referrals;
    /**
     * @var UserReferral[]
     * @ORM\OneToMany(targetEntity="UserReferral", mappedBy="referral")
     */
    private $partners;
    /**
     * @var UserFractal[]
     * @ORM\OneToMany(targetEntity="UserFractal", mappedBy="user")
     */
    private $userFractals;

    /**
     * User constructor.
     */
    public function __construct() {
        $this->sbtTokens = new ArrayCollection();
        $this->userAchievements = new ArrayCollection();
        $this->userQuests = new ArrayCollection();
        $this->userQuestTasks = new ArrayCollection();
        $this->referrals = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->userFractals = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return User
     */
    public function setAddress(string $address): User
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    /**
     * @param DateTimeImmutable $registeredAt
     * @return User
     */
    public function setRegisteredAt(DateTimeImmutable $registeredAt): User
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    /**
     * @return Collection|SbtToken[]
     */
    public function getSbtTokens(): Collection
    {
        return $this->sbtTokens;
    }

    /**
     * @return array
     */
    public function getLew3lupIdTokens(): array
    {
        $lew3lupIdTokens = [];
        foreach ($this->sbtTokens as $sbtToken) {
            if ($sbtToken->getType() === SbtToken::TYPE_LEW3LUP_ID && $sbtToken->getRevokeEvent() === null) {
                $lew3lupIdTokens[] = $sbtToken;
            }
        }
        return $lew3lupIdTokens;
    }

    /**
     * @return array
     */
    public function getBabtTokens(): array
    {
        $babtTokens = [];
        foreach ($this->sbtTokens as $sbtToken) {
            if ($sbtToken->getType() === SbtToken::TYPE_BABT && $sbtToken->getRevokeEvent() === null) {
                $babtTokens[] = $sbtToken;
            }
        }
        return $babtTokens;
    }

    /**
     * @return Collection|UserAchievement[]
     */
    public function getUserAchievements(): Collection
    {
        return $this->userAchievements;
    }

    /**
     * @return Collection|UserQuest[]
     */
    public function getUserQuests(): Collection
    {
        return $this->userQuests;
    }

    /**
     * @return Collection|UserQuestTask[]
     */
    public function getUserQuestTasks(): Collection
    {
        return $this->userQuestTasks;
    }

    /**
     * @return Collection|UserReferral[]
     */
    public function getReferrals(): Collection
    {
        return $this->referrals;
    }

    /**
     * @return Collection|UserReferral[]
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    /**
     * @return UserFractal|null
     */
    public function getUserFractal(): ?UserFractal
    {
        return count($this->userFractals) > 0
            ? $this->userFractals->toArray()[0]
            : null;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        $userFractal = $this->getUserFractal();
        return ($userFractal && $userFractal->isApproved()) || !empty($this->getBabtTokens());
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'address'           => $this->address,
            'email'             => $this->email,
            'lew3lupIdTokens'   => $this->getLew3lupIdTokens(),
        ];
    }
}