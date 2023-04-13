<?php

namespace App\Repository;

use App\Entity\CategoryDescription;
use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryDescriptionRepository
 * @package App\Repository
 *
 * @method CategoryDescription findOneBy(array $criteria, ?array $orderBy = null)
 * @method CategoryDescription[] findAll()
 * @method CategoryDescription[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryDescriptionRepository extends EntityRepository
{

}