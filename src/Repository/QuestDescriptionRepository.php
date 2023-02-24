<?php

namespace App\Repository;

use App\Entity\QuestDescription;
use Doctrine\ORM\EntityRepository;

/**
 * Class QuestDescriptionRepository
 * @package App\Repository
 *
 * @method QuestDescription findOneBy(array $criteria, ?array $orderBy = null)
 * @method QuestDescription[] findAll()
 * @method QuestDescription[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuestDescriptionRepository extends EntityRepository
{

}