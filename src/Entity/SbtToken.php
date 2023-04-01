<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность SBT-токена
 *
 * @ORM\Table(name="sbt_tokens")
 * @ORM\Entity(repositoryClass="App\Repository\SbtTokenRepository")
 *
 * Class SbtToken
 * @package App\Entity
 */
class SbtToken extends Serializable
{
    public const TYPE_LEW3LUP_ID = 1;
    public const TYPE_BABT = 2;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $type;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sbtTokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * ID блокчейна
     *
     * @var int
     * @ORM\Column(type="integer", name="chain_id")
     */
    private $chainId;
    /**
     * Адрес контракта
     *
     * @var string
     * @ORM\Column(type="string", length=42)
     */
    private $contract;
    /**
     * ID токена в рамках контракта
     *
     * @var int
     * @ORM\Column(type="integer", name="id_in_contract")
     */
    private $idInContract;
    /**
     * ID токена в рамках контракта в шестнадцатиричном виде
     *
     * @var string
     * @ORM\Column(type="string", length=66, name="id_in_contract_hex")
     */
    private $idInContractHex;
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
     * @ORM\JoinColumn(name="attest_event_id", referencedColumnName="id")
     */
    private $attestEvent;
    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="revoke_event_id", referencedColumnName="id", nullable=true)
     */
    private $revokeEvent;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return SbtToken
     */
    public function setType(int $type): SbtToken
    {
        $this->type = $type;
        return $this;
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
     * @return SbtToken
     */
    public function setUser(User $user): SbtToken
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getChainId(): int
    {
        return $this->chainId;
    }

    /**
     * @param int $chainId
     * @return SbtToken
     */
    public function setChainId(int $chainId): SbtToken
    {
        $this->chainId = $chainId;
        return $this;
    }

    /**
     * @return string
     */
    public function getContract(): string
    {
        return $this->contract;
    }

    /**
     * @param string $contract
     * @return SbtToken
     */
    public function setContract(string $contract): SbtToken
    {
        $this->contract = $contract;
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
     * @return SbtToken
     */
    public function setIdInContract(int $idInContract): SbtToken
    {
        $this->idInContract = $idInContract;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdInContractHex(): string
    {
        return $this->idInContractHex;
    }

    /**
     * @param string $idInContractHex
     * @return SbtToken
     */
    public function setIdInContractHex(string $idInContractHex): SbtToken
    {
        $this->idInContractHex = $idInContractHex;
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
     * @return SbtToken
     */
    public function setOwnerAddress(string $ownerAddress): SbtToken
    {
        $this->ownerAddress = $ownerAddress;
        return $this;
    }

    /**
     * @return Event
     */
    public function getAttestEvent(): Event
    {
        return $this->attestEvent;
    }

    /**
     * @param Event $attestEvent
     * @return SbtToken
     */
    public function setAttestEvent(Event $attestEvent): SbtToken
    {
        $this->attestEvent = $attestEvent;
        return $this;
    }

    /**
     * @return Event
     */
    public function getRevokeEvent(): ?Event
    {
        return $this->revokeEvent;
    }

    /**
     * @param Event $revokeEvent
     * @return SbtToken
     */
    public function setRevokeEvent(Event $revokeEvent): SbtToken
    {
        $this->revokeEvent = $revokeEvent;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'chainId'       => $this->chainId,
            'idInContract'  => $this->idInContract
        ];
    }
}