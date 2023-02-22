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
 * @package App\Model\Event\Entity
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
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="registered_at")
     */
    private $registeredAt;
    /**
     * @var BabtToken[]
     * @ORM\OneToMany(targetEntity="BabtToken", mappedBy="user")
     */
    private $babtTokens;

    /**
     * User constructor.
     */
    public function __construct() {
        $this->babtTokens = new ArrayCollection();
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
     * @return Collection|BabtToken[]
     */
    public function getBabtTokens(): Collection
    {
        return $this->babtTokens;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'address'   => $this->address,
        ];
    }
}