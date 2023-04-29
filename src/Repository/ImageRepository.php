<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\ORM\EntityRepository;

/**
 * Class ImageRepository
 * @package App\Repository
 *
 * @method Image findOneBy(array $criteria, ?array $orderBy = null)
 * @method Image[] findAll()
 * @method Image[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param int $adminId
     * @return Image|null
     */
    public function findOneByIdAndAdminId(int $id, int $adminId): ?Image
    {
        $result = $this->createQueryBuilder('i')
            ->innerJoin('i.game', 'g')
            ->innerJoin('g.admins', 'ga')
            ->innerJoin('ga.user', 'u')
            ->where('i.id = :id')
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