<?php

namespace App\Command;

use App\Service\EventParser\EventParserInterface;
use App\Service\EventService;
use App\Service\LastParsedBlockService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParseContractEventsCommand extends Command
{
    private const SLEEP_TIME = 1;

    /** @var EntityManagerInterface */
    protected $em;
    /** @var EventService */
    protected $eventService;
    /** @var LastParsedBlockService */
    protected $lastParsedBlockService;
    /** @var ParameterBagInterface */
    protected $parameterBag;
    /** @var LoggerInterface */
    protected $logger;

    /**
     * ParseContractEventsCommand constructor.
     * @param EntityManagerInterface $em
     * @param EventService $eventService
     * @param LastParsedBlockService $lastParsedBlockService
     * @param ParameterBagInterface $parameterBag
     * @param LoggerInterface $logger
     * @param string|null $name
     */
    public function __construct(
        EntityManagerInterface $em,
        EventService $eventService,
        LastParsedBlockService $lastParsedBlockService,
        ParameterBagInterface $parameterBag,
        LoggerInterface $logger,
        string $name = null
    ) {
        $this->em = $em;
        $this->eventService = $eventService;
        $this->lastParsedBlockService = $lastParsedBlockService;
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('parse-events')->setDescription('Parse smart contracts events');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->parseInCycle(
            $this->parameterBag->get('blockchain'),
            $this->parameterBag->get('eventsParsingIterations'),
            $output
        );
        return 0;
    }

    /**
     * @param array $blockchainConfigs
     * @param int $iterations
     * @param OutputInterface $output
     */
    protected function parseInCycle(array $blockchainConfigs, int $iterations, OutputInterface $output): void
    {
        $startTime = time();
        $message = 'Parsing cycle is started';
        $output->writeln($message);
        $this->logger->info($message);
        for ($i = 0; $i < $iterations; $i++) {
            $this->parse($blockchainConfigs, $output);
            sleep(self::SLEEP_TIME);
        }
        $endTime = time();
        $message = 'Cycle done in ' . ($endTime - $startTime) . ' seconds';
        $output->writeln($message);
        $this->logger->info($message);
    }

    /**
     * @param array $blockchainConfigs
     * @param OutputInterface $output
     */
    protected function parse(array $blockchainConfigs, OutputInterface $output): void
    {
        foreach ($blockchainConfigs as $chainId => $config) {
            try {
                $contractAddress = $config['contractAddress'];
                if (!$contractAddress) {
                    continue;
                }
                $message = 'Parsing events for chainId=' . $chainId . ', contractAddress=' . $contractAddress;
                $output->writeln($message);
                $this->logger->info($message);
                $lastParsedBlock = $this->lastParsedBlockService->getLastParsedBlock($chainId, $contractAddress);
                $parserClass = '\App\Service\EventParser\\' . $config['parser'];
                /** @var EventParserInterface $parser */
                $parser = new $parserClass($chainId, $config);
                $events = $parser->parseEvents($lastParsedBlock);
                $this->eventService->saveEvents($chainId, $events);
                $this->em->flush();
            } catch (Exception $e) {
                $output->writeln($e->getMessage());
                $output->writeln($e->getTraceAsString());
                $this->logger->error($e->getMessage());
            }
        }
    }
}