<?php

namespace App\Repository;

use App\Entity\Achievement;
use Doctrine\ORM\EntityRepository;

/**
 * Class AchievementRepository
 * @package App\Repository
 *
 * @method Achievement findOneBy(array $criteria, ?array $orderBy = null)
 * @method Achievement[] findAll()
 * @method Achievement[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class AchievementRepository extends EntityRepository
{

}