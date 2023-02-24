<?php

namespace App\Repository;

use App\Entity\GameAdmin;
use Doctrine\ORM\EntityRepository;

/**
 * Class GameAdminRepository
 * @package App\Repository
 *
 * @method GameAdmin findOneBy(array $criteria, ?array $orderBy = null)
 * @method GameAdmin[] findAll()
 * @method GameAdmin[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class GameAdminRepository extends EntityRepository
{

}