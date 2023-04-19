<?php

namespace App\Repository;

use App\Entity\Backer;
use Doctrine\ORM\EntityRepository;

/**
 * Class BackerRepository
 * @package App\Repository
 *
 * @method Backer findOneBy(array $criteria, ?array $orderBy = null)
 * @method Backer[] findAll()
 * @method Backer[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class BackerRepository extends EntityRepository
{

}