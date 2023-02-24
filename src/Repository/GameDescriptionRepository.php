<?php

namespace App\Repository;

use App\Entity\GameDescription;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameDescriptionRepository
 * @package App\Repository
 *
 * @method GameDescription findOneBy(array $criteria, ?array $orderBy = null)
 * @method GameDescription[] findAll()
 * @method GameDescription[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class GameDescriptionRepository extends EntityRepository
{

}