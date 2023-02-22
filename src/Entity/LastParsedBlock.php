<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность для учёта последних обработанных блоков для разных сетей при парсинге событий
 *
 * @ORM\Table(name="last_parsed_blocks")
 * @ORM\Entity(repositoryClass="App\Repository\LastParsedBlockRepository")
 *
 * Class LastParsedBlock
 * @package App\Model\Event\Entity
 */
class LastParsedBlock
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
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
     * Номер последнего спарсенного блока
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    private $block;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="updated_at")
     */
    private $updatedAt;

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
     * @return LastParsedBlock
     */
    public function setChainId(int $chainId): LastParsedBlock
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
     * @return LastParsedBlock
     */
    public function setContract(string $contract): LastParsedBlock
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlock(): int
    {
        return $this->block;
    }

    /**
     * @param int $block
     * @return LastParsedBlock
     */
    public function setBlock(int $block): LastParsedBlock
    {
        if ($this->block === null || $block > $this->block) {
            $this->block = $block;
            $this->updatedAt = new DateTimeImmutable();
        }
        return $this;
    }
}