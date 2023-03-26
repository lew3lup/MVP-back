<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameRepository
 * @package App\Repository
 *
 * @method Game findOneBy(array $criteria, ?array $orderBy = null)
 * @method Game[] findAll()
 * @method Game[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return Game|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?Game
    {
        $result = $this->createQueryBuilder('g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('g.id = :id')
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