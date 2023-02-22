<?php

namespace App\Repository;

use App\Entity\LastParsedBlock;
use Doctrine\ORM\EntityRepository;

/**
 * Class LastParsedBlockRepository
 * @package App\Repository
 *
 * @method LastParsedBlock findOneBy(array $criteria, ?array $orderBy = null)
 * @method LastParsedBlock[] findAll()
 * @method LastParsedBlock[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class LastParsedBlockRepository extends EntityRepository
{
    /**
     * @param int $chainId
     * @param string $contract
     * @return LastParsedBlock|null
     */
    public function findOneByChainIdAndContract(int $chainId, string $contract): ?LastParsedBlock
    {
        return $this->findOneBy(['chainId' => $chainId, 'contract' => $contract]);
    }
}