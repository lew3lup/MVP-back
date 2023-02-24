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

}