<?php

namespace App\Repository;

use App\Entity\GameBacker;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameBackerRepository
 * @package App\Repository
 *
 * @method GameBacker findOneBy(array $criteria, ?array $orderBy = null)
 * @method GameBacker[] findAll()
 * @method GameBacker[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class GameBackerRepository extends EntityRepository
{

}