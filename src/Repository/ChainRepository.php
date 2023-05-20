<?php

namespace App\Repository;

use App\Entity\Chain;
use Doctrine\ORM\EntityRepository;

/**
 * Class ChainRepository
 * @package App\Repository
 *
 * @method Chain findOneBy(array $criteria, ?array $orderBy = null)
 * @method Chain[] findAll()
 * @method Chain[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class ChainRepository extends EntityRepository
{
    /**
     * @param array $ids
     * @return Chain[]|array
     */
    public function findByIds(array $ids): array
    {
        return $this->findBy(['id' => $ids]);
    }
}