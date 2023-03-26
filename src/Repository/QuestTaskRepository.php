<?php

namespace App\Repository;

use App\Entity\QuestTask;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestTaskRepository
 * @package App\Repository
 *
 * @method QuestTask findOneBy(array $criteria, ?array $orderBy = null)
 * @method QuestTask[] findAll()
 * @method QuestTask[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestTaskRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return QuestTask|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?QuestTask
    {
        $result = $this->createQueryBuilder('qt')
            ->innerJoin('qt.quest', 'q')
            ->innerJoin('q.game', 'g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('qt.id = :id')
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