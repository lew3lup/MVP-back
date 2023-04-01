<?php

namespace App\Repository;

use App\Entity\QuestTaskDescription;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestTaskDescriptionRepository
 * @package App\Repository
 *
 * @method QuestTaskDescription findOneBy(array $criteria, ?array $orderBy = null)
 * @method QuestTaskDescription[] findAll()
 * @method QuestTaskDescription[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestTaskDescriptionRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return QuestTaskDescription|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?QuestTaskDescription
    {
        $result = $this->createQueryBuilder('qtd')
            ->innerJoin('qtd.questTask', 'qt')
            ->innerJoin('qt.quest', 'q')
            ->innerJoin('q.game', 'g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('qtd.id = :id')
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