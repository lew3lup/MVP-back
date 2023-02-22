<?php

namespace App\Service\EventParser;

use App\Entity\Event;
use App\Entity\LastParsedBlock;

interface EventParserInterface
{
    /**
     * EventParserInterface constructor.
     * @param int $chainId
     * @param array $config
     */
    public function __construct(int $chainId, array $config);

    /**
     * @param LastParsedBlock $lastParsedBlock
     * @return Event[]
     */
    public function parseEvents(LastParsedBlock $lastParsedBlock): array;
}