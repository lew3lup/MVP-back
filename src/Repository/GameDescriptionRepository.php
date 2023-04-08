<?php

namespace App\Repository;

use App\Entity\GameDescription;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameDescriptionRepository
 * @package App\Repository
 *
 * @method GameDescription findOneBy(array $criteria, ?array $orderBy = null)
 * @method GameDescription[] findAll()
 * @method GameDescription[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class GameDescriptionRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return GameDescription|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?GameDescription
    {
        $result = $this->createQueryBuilder('gd')
            ->innerJoin('gd.game', 'g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('gd.id = :id')
            ->andWhere('u.id = :adminId')
            ->setParameter('id', $id)
            ->setParameter('adminId', $adminId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }
}