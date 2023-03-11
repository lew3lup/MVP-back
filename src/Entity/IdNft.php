<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность NFT LEW3L-UP ID
 *
 * @ORM\Table(name=id_nfts")
 * @ORM\Entity(repositoryClass="App\Repository\IdNftRepository")
 *
 * Class IdNft
 * @package App\Entity
 */
class IdNft
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="idNfts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * ID токена в рамках контракта
     *
     * @var int
     * @ORM\Column(type="integer", name="id_in_contract")
     */
    private $idInContract;
    /**
     * ETH-адрес владельца
     *
     * @var string
     * @ORM\Column(type="string", length=42, name="owner_address")
     */
    private $ownerAddress;
    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

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
     * @return IdNft
     */
    public function setUser(User $user): IdNft
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdInContract(): int
    {
        return $this->idInContract;
    }

    /**
     * @param int $idInContract
     * @return IdNft
     */
    public function setIdInContract(int $idInContract): IdNft
    {
        $this->idInContract = $idInContract;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerAddress(): string
    {
        return $this->ownerAddress;
    }

    /**
     * @param string $ownerAddress
     * @return IdNft
     */
    public function setOwnerAddress(string $ownerAddress): IdNft
    {
        $this->ownerAddress = $ownerAddress;
        return $this;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     * @return IdNft
     */
    public function setEvent(Event $event): IdNft
    {
        $this->event = $event;
        return $this;
    }
}