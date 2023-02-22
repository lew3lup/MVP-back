<?php

namespace App\Service;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class EventService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var EventRepository */
    private $eventRepo;
    /** @var LoggerInterface */
    private $logger;

    /**
     * EventService constructor.
     * @param EntityManagerInterface $em
     * @param EventRepository $eventRepo
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        EventRepository $eventRepo,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->eventRepo = $eventRepo;
        $this->logger = $logger;
    }

    /**
     * @param int $chainId
     * @param Event[] $events
     * @throws Exception
     */
    public function saveEvents(int $chainId, array $events): void
    {
        $transactions = [];
        foreach ($events as $event) {
            $transactions[] = $event->getTransactionHash();
        }
        //Проверяем события, не были ли они уже были добавлены в базу ранее
        $alreadyParsedEvents = $this->eventRepo->findByChainAndTransactions($chainId, $transactions);
        foreach ($events as $event) {
            $alreadyParsed = false;
            foreach ($alreadyParsedEvents as $parsedEvent) {
                if (
                    $event->getTransactionHash() === $parsedEvent->getTransactionHash() &&
                    $event->getName() === $parsedEvent->getName()
                ) {
                    $alreadyParsed = true;
                    break;
                }
            }
            if (!$alreadyParsed) {
                $this->saveEvent($event);
            }
        }
    }

    /**
     * @param Event $event
     * @throws Exception
     */
    private function saveEvent(Event $event): void
    {
        switch ($event->getName()) {
            //ToDo
        }
        $this->em->persist($event);
        $this->em->flush();
    }
}