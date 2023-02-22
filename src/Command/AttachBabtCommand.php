<?php

namespace App\Command;

use App\Entity\BabtToken;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AttachBabtCommand extends Command
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var EventRepository */
    protected $eventRepo;
    /** @var UserRepository */
    protected $userRepo;

    /**
     * AttachBabtCommand constructor.
     * @param EntityManagerInterface $em
     * @param EventRepository $eventRepo
     * @param UserRepository $userRepo
     * @param string|null $name
     */
    public function __construct(
        EntityManagerInterface $em,
        EventRepository $eventRepo,
        UserRepository $userRepo,
        string $name = null
    ) {
        $this->em = $em;
        $this->eventRepo = $eventRepo;
        $this->userRepo = $userRepo;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('attach-babt')->setDescription('Attach BABT tokens to users');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $unattachedBabt = $this->eventRepo->findUnattachedBabt();
        if ($unattachedBabt) {
            foreach ($unattachedBabt as $babt) {
                $user = $this->userRepo->findOneBy(['id' => $babt['user_id']]);
                $event = $this->eventRepo->findOneBy(['id' => $babt['event_id']]);
                $babtToken = (new BabtToken())
                    ->setUser($user)
                    ->setEvent($event)
                    ->setIdInContract(hexdec($event->getTopic2()));
                $this->em->persist($babtToken);
            }
            $this->em->flush();
        }
        return 0;
    }
}