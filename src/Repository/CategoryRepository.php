<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package App\Repository
 *
 * @method Category findOneBy(array $criteria, ?array $orderBy = null)
 * @method Category[] findAll()
 * @method Category[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends EntityRepository
{

}