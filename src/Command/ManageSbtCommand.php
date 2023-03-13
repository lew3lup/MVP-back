<?php

namespace App\Command;

use App\Entity\SbtToken;
use App\Repository\EventRepository;
use App\Repository\SbtTokenRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ManageSbtCommand extends Command
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var EventRepository */
    protected $eventRepo;
    /** @var UserRepository */
    protected $userRepo;
    /** @var SbtTokenRepository */
    protected $sbtRepo;

    /**
     * ManageSbtCommand constructor.
     * @param EntityManagerInterface $em
     * @param EventRepository $eventRepo
     * @param UserRepository $userRepo
     * @param SbtTokenRepository $sbtRepo
     * @param string|null $name
     */
    public function __construct(
        EntityManagerInterface $em,
        EventRepository $eventRepo,
        UserRepository $userRepo,
        SbtTokenRepository $sbtRepo,
        string $name = null
    ) {
        $this->em = $em;
        $this->eventRepo = $eventRepo;
        $this->userRepo = $userRepo;
        $this->sbtRepo = $sbtRepo;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('manage-sbt')->setDescription('Manage SBT-tokens');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $unattachedSbt = $this->eventRepo->findUnattachedSbt();
        if ($unattachedSbt) {
            foreach ($unattachedSbt as $sbt) {
                $user = $this->userRepo->findOneBy(['id' => $sbt['user_id']]);
                $event = $this->eventRepo->findOneBy(['id' => $sbt['event_id']]);
                //ToDo: сохранение адреса владельца!
                $sbtToken = (new SbtToken())
                    ->setUser($user)
                    ->setAttestEvent($event)
                    ->setChainId($event->getChainId())
                    ->setContract($event->getContract())
                    //->setOwnerAddress()
                    ->setIdInContract(hexdec($event->getTopic2()));
                $this->em->persist($sbtToken);
            }
            $this->em->flush();
        }
        $sbtToRevoke = $this->eventRepo->findSbtToRevoke();
        if ($sbtToRevoke) {
            foreach ($sbtToRevoke as $sbt) {
                $sbtToken = $this->sbtRepo->findOneBy(['id' => $sbt['sbt_id']]);
                $event = $this->eventRepo->findOneBy(['id' => $sbt['event_id']]);
                $sbtToken->setRevokeEvent($event);
            }
            $this->em->flush();
        }
        return 0;
    }
}