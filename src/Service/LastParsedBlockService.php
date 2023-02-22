<?php

namespace App\Service;

use App\Entity\LastParsedBlock;
use App\Repository\LastParsedBlockRepository;
use Doctrine\ORM\EntityManagerInterface;

class LastParsedBlockService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var LastParsedBlockRepository */
    private $lastParsedBlockRepo;

    /**
     * LastParsedBlockService constructor.
     * @param EntityManagerInterface $em
     * @param LastParsedBlockRepository $lastParsedBlockRepo
     */
    public function __construct(
        EntityManagerInterface $em,
        LastParsedBlockRepository $lastParsedBlockRepo
    ) {
        $this->em = $em;
        $this->lastParsedBlockRepo = $lastParsedBlockRepo;
    }

    /**
     * @param int $chainId
     * @param string $contract
     * @return LastParsedBlock
     */
    public function getLastParsedBlock(int $chainId, string $contract): LastParsedBlock
    {
        $lastParsedBlock = $this->lastParsedBlockRepo->findOneByChainIdAndContract($chainId, $contract);
        if (!$lastParsedBlock) {
            $lastParsedBlock = (new LastParsedBlock())
                ->setChainId($chainId)
                ->setContract($contract)
                ->setBlock(0)
            ;
            $this->em->persist($lastParsedBlock);
        }
        return $lastParsedBlock;
    }
}