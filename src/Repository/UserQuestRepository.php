<?php

namespace App\Repository;

use App\Entity\UserQuest;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserQuestRepository
 * @package App\Repository
 *
 * @method UserQuest findOneBy(array $criteria, ?array $orderBy = null)
 * @method UserQuest[] findAll()
 * @method UserQuest[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuestRepository extends EntityRepository
{

}