<?php

namespace App\Repository;

use App\Entity\UserQuestTask;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserQuestTaskRepository
 * @package App\Repository
 *
 * @method UserQuestTask findOneBy(array $criteria, ?array $orderBy = null)
 * @method UserQuestTask[] findAll()
 * @method UserQuestTask[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuestTaskRepository extends EntityRepository
{

}