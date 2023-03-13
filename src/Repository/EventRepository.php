<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityRepository;

/**
 * Class EventRepository
 * @package App\Repository
 *
 * @method Event findOneBy(array $criteria, ?array $orderBy = null)
 * @method Event[] findAll()
 * @method Event[] findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends EntityRepository
{
    /**
     * @param int $chainId
     * @param string[] $transactions
     * @return Event[]
     */
    public function findByChainAndTransactions(int $chainId, array $transactions)
    {
        return $this->findBy(['chainId' => $chainId, 'transactionHash' => $transactions]);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function findUnattachedSbt(): array
    {
        $sql = '
            SELECT u.id as user_id, e.id as event_id FROM users u
            INNER JOIN events e ON (substring(u.address from 3) = substring(e.topic_1 from 27) AND e.name = :name)
            WHERE e.id NOT IN (SELECT attest_event_id FROM sbt_tokens)
        ';
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $result = $stmt->executeQuery(['name' => Event::NAME_SBT_ATTEST]);
        return $result->fetchAllAssociative();
    }
}