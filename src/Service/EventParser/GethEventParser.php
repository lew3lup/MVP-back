<?php

namespace App\Service\EventParser;

use App\Entity\Event;
use App\Entity\LastParsedBlock;
use App\Service\GethApiService;
use DateTimeImmutable;
use Exception;

class GethEventParser implements EventParserInterface
{
    private const BLOCKS_LIMIT = 2000;

    /** @var int */
    private $chainId;
    /** @var string */
    private $contractAddress;
    /** @var GethApiService */
    private $gethApiService;
    /** @var int */
    private $startBlock;

    /**
     * GethEventParser constructor.
     * @param int $chainId
     * @param array $config
     */
    public function __construct(int $chainId, array $config)
    {
        $this->chainId = $chainId;
        $this->contractAddress = $config['contractAddress'];
        $this->startBlock = $config['startBlock'];
        $this->gethApiService = new GethApiService($config['apiDomain']);
    }

    /**
     * @param LastParsedBlock $lastParsedBlock
     * @return Event[]
     * @throws Exception
     */
    public function parseEvents(LastParsedBlock $lastParsedBlock): array
    {
        //Получение интервала блоков для парсинга
        $fromBlock = $lastParsedBlock->getBlock();
        if ($this->startBlock > $fromBlock) {
            $fromBlock = $this->startBlock;
        }
        $fromBlockHex = '0x' . dechex($fromBlock);
        $toBlockHex = $this->gethApiService->getCurrentBlockNumber();
        $toBlock = hexdec(str_replace('0x', '', $toBlockHex));
        if ($toBlock - $fromBlock > self::BLOCKS_LIMIT) {
            $toBlock = $fromBlock + self::BLOCKS_LIMIT;
            $toBlockHex = '0x' . dechex($toBlock);
        }
        //Парсинг
        $logs = $this->gethApiService->getLogs($this->contractAddress, $fromBlockHex, $toBlockHex);
        $events = [];
        $nowTime = new DateTimeImmutable();
        foreach ($logs as $log) {
            $events[] = (new Event())
                ->setChainId($this->chainId)
                ->setContract($this->contractAddress)
                ->setName($log['topics'][0])
                ->setTopic1(!empty($log['topics'][1]) ? $log['topics'][1] : null)
                ->setTopic2(!empty($log['topics'][2]) ? $log['topics'][2] : null)
                ->setTopic3(!empty($log['topics'][3]) ? $log['topics'][3] : null)
                ->setData($log['data'] !== '0x' ? $log['data'] : null)
                ->setBlockNumber(hexdec($log['blockNumber']))
                ->setTimestamp($nowTime)
                ->setTransactionHash($log['transactionHash'])
                ->setParsedAt($nowTime);
        }
        $lastParsedBlock->setBlock($toBlock);
        return $events;
    }
}