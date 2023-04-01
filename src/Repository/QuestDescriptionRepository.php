<?php

namespace App\Repository;

use App\Entity\QuestDescription;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestDescriptionRepository
 * @package App\Repository
 *
 * @method QuestDescription findOneBy(array $criteria, ?array $orderBy = null)
 * @method QuestDescription[] findAll()
 * @method QuestDescription[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestDescriptionRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return QuestDescription|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?QuestDescription
    {
        $result = $this->createQueryBuilder('qd')
            ->innerJoin('qd.quest', 'q')
            ->innerJoin('q.game', 'g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('qd.id = :id')
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