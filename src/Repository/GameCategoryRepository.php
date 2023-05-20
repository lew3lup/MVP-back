<?php

namespace App\Repository;

use App\Entity\GameCategory;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameCategoryRepository
 * @package App\Repository
 *
 * @method GameCategory findOneBy(array $criteria, ?array $orderBy = null)
 * @method GameCategory[] findAll()
 * @method GameCategory[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class GameCategoryRepository extends EntityRepository
{

}