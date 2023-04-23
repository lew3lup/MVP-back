<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\ORM\EntityRepository;

/**
 * Class ImageRepository
 * @package App\Repository
 *
 * @method Image findOneBy(array $criteria, ?array $orderBy = null)
 * @method Image[] findAll()
 * @method Image[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends EntityRepository
{

}