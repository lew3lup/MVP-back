<?php

namespace App\Repository;

use App\Entity\Quest;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestRepository
 * @package App\Repository
 *
 * @method Quest findOneBy(array $criteria, ?array $orderBy = null)
 * @method Quest[] findAll()
 * @method Quest[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestRepository extends EntityRepository
{

}