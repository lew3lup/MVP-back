<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseBabtEventsCommand extends ParseContractEventsCommand
{
    protected function configure(): void
    {
        $this->setName('parse-babt')->setDescription('Parse BABT token events');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->parseInCycle(
            $this->parameterBag->get('babt'),
            $this->parameterBag->get('babtParsingIterations'),
            $output
        );
        return 0;
    }
}