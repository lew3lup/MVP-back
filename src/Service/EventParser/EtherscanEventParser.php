<?php

namespace App\Service\EventParser;

use App\Entity\Event;
use App\Entity\LastParsedBlock;
use App\Service\EtherscanApiService;
use DateTimeImmutable;
use Exception;

class EtherscanEventParser implements EventParserInterface
{
    /** @var int */
    private $chainId;
    /** @var string */
    private $contractAddress;
    /** @var EtherscanApiService */
    private $etherscanApiService;

    /**
     * EtherscanEventParser constructor.
     * @param int $chainId
     * @param array $config
     */
    public function __construct(int $chainId, array $config)
    {
        $this->chainId = $chainId;
        $this->contractAddress = $config['contractAddress'];
        $this->etherscanApiService = new EtherscanApiService($config['apiDomain'], $config['apiKey']);
    }

    /**
     * @param LastParsedBlock $lastParsedBlock
     * @return Event[]
     * @throws Exception
     */
    public function parseEvents(LastParsedBlock $lastParsedBlock): array
    {
        $logs = $this->etherscanApiService->getLogs($this->contractAddress, $lastParsedBlock->getBlock());
        $events = [];
        $nowTime = new DateTimeImmutable();
        foreach ($logs as $log) {
            $blockNumber = hexdec($log['blockNumber']);
            $events[] = (new Event())
                ->setChainId($this->chainId)
                ->setContract($this->contractAddress)
                ->setName($log['topics'][0])
                ->setTopic1(!empty($log['topics'][1]) ? $log['topics'][1] : null)
                ->setTopic2(!empty($log['topics'][2]) ? $log['topics'][2] : null)
                ->setTopic3(!empty($log['topics'][3]) ? $log['topics'][3] : null)
                ->setData($log['data'] !== '0x' ? $log['data'] : null)
                ->setBlockNumber($blockNumber)
                ->setTimestamp(
                    (new DateTimeImmutable())->setTimestamp(hexdec($log['timeStamp']))
                )
                ->setTransactionHash($log['transactionHash'])
                ->setParsedAt($nowTime);
            $lastParsedBlock->setBlock($blockNumber);
        }
        return $events;
    }
}