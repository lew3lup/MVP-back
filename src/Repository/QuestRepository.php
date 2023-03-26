<?php

namespace App\Repository;

use App\Entity\Quest;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestRepository
 * @package App\Repository
 *
 * @method Quest findOneBy(array $criteria, ?array $orderBy = null)
 * @method Quest[] findAll()
 * @method Quest[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return Quest|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?Quest
    {
        $result = $this->createQueryBuilder('q')
            ->innerJoin('q.game', 'g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('q.id = :id')
            ->andWhere('q.deleted = false')
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