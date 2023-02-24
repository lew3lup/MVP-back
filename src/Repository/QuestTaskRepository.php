<?php

namespace App\Repository;

use App\Entity\QuestTask;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestTaskRepository
 * @package App\Repository
 *
 * @method QuestTask findOneBy(array $criteria, ?array $orderBy = null)
 * @method QuestTask[] findAll()
 * @method QuestTask[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestTaskRepository extends EntityRepository
{

}