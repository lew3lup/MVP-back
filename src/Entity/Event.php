<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность события в смарт-контракте
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 *
 * Class Event
 * @package App\Entity
 */
class Event
{
    public const NAME_SBT_ATTEST    = '0xe9274a84b19e9428826de6bae8c48329354f8f0e73f771b97cae2d9dccd45a27';
    public const NAME_SBT_REVOKE    = '0xec9ab91322523c899ede7830ec9bfc992b5981cdcc27b91162fb23de5791117b';
    public const NAME_SBT_BURN      = '0xcc16f5dbb4873280815c1ee09dbd06736cffcc184412cf7a71a0fdb75d397ca5';

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * ID блокчейна
     *
     * @var int
     * @ORM\Column(type="integer", name="chain_id")
     */
    private $chainId;
    /**
     * @var string
     * @ORM\Column(type="string", length=42)
     */
    private $contract;
    /**
     * @var string
     * @ORM\Column(type="string", length=66, name="transaction_hash")
     */
    private $transactionHash;
    /**
     * @var string
     * @ORM\Column(type="string", length=66)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string", length=66, name="topic_1", nullable=true)
     */
    private $topic1;
    /**
     * @var string
     * @ORM\Column(type="string", length=66, name="topic_2", nullable=true)
     */
    private $topic2;
    /**
     * @var string
     * @ORM\Column(type="string", length=66, name="topic_3", nullable=true)
     */
    private $topic3;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $data;
    /**
     * @var integer
     * @ORM\Column(type="integer", name="block_number")
     */
    private $blockNumber;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="timestamp")
     */
    private $timestamp;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="parsed_at")
     */
    private $parsedAt;

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
    public function getChainId(): int
    {
        return $this->chainId;
    }

    /**
     * @param int $chainId
     * @return $this
     */
    public function setChainId(int $chainId): Event
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
     * @return Event
     */
    public function setContract(string $contract): Event
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionHash(): string
    {
        return $this->transactionHash;
    }

    /**
     * @param string $transactionHash
     * @return $this
     */
    public function setTransactionHash(string $transactionHash): Event
    {
        $this->transactionHash = $transactionHash;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Event
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTopic1(): string
    {
        return $this->topic1;
    }

    /**
     * @param string|null $topic1
     * @return $this
     */
    public function setTopic1(?string $topic1): Event
    {
        $this->topic1 = $topic1;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTopic2(): ?string
    {
        return $this->topic2;
    }

    /**
     * @param string|null $topic2
     * @return $this
     */
    public function setTopic2(?string $topic2): Event
    {
        $this->topic2 = $topic2;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTopic3(): ?string
    {
        return $this->topic3;
    }

    /**
     * @param string|null $topic3
     * @return $this
     */
    public function setTopic3(?string $topic3): Event
    {
        $this->topic3 = $topic3;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     * @return $this
     */
    public function setData(?string $data): Event
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlockNumber(): int
    {
        return $this->blockNumber;
    }

    /**
     * @param int $blockNumber
     * @return $this
     */
    public function setBlockNumber(int $blockNumber): Event
    {
        $this->blockNumber = $blockNumber;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @param DateTimeImmutable $timestamp
     * @return $this
     */
    public function setTimestamp(DateTimeImmutable $timestamp): Event
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getParsedAt(): DateTimeImmutable
    {
        return $this->parsedAt;
    }

    /**
     * @param DateTimeImmutable $parsedAt
     * @return Event
     */
    public function setParsedAt(DateTimeImmutable $parsedAt): Event
    {
        $this->parsedAt = $parsedAt;
        return $this;
    }
}