<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package App\Repository
 *
 * @method User findOneBy(array $criteria, ?array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string $address
     * @return User|null
     */
    public function findOneByAddress(string $address): ?User
    {
        return $this->findOneBy(['address' => $address]);
    }
}