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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ManageSbtCommand extends Command
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var ParameterBagInterface */
    protected $parameterBag;
    /** @var EventRepository */
    protected $eventRepo;
    /** @var UserRepository */
    protected $userRepo;
    /** @var SbtTokenRepository */
    protected $sbtRepo;

    /**
     * ManageSbtCommand constructor.
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface $parameterBag
     * @param EventRepository $eventRepo
     * @param UserRepository $userRepo
     * @param SbtTokenRepository $sbtRepo
     * @param string|null $name
     */
    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $parameterBag,
        EventRepository $eventRepo,
        UserRepository $userRepo,
        SbtTokenRepository $sbtRepo,
        string $name = null
    ) {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
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
        $babtConfig = $this->parameterBag->get('babt');
        $unattachedSbt = $this->eventRepo->findUnattachedSbt();
        if ($unattachedSbt) {
            foreach ($unattachedSbt as $sbt) {
                $user = $this->userRepo->findOneBy(['id' => $sbt['user_id']]);
                $event = $this->eventRepo->findOneBy(['id' => $sbt['event_id']]);
                $type = SbtToken::TYPE_LEW3LUP_ID;
                foreach ($babtConfig as $chainId => $config) {
                    if ($event->getChainId() === $chainId && $event->getContract() === $config['contractAddress']) {
                        $type = SbtToken::TYPE_BABT;
                        break;
                    }
                }
                $sbtToken = (new SbtToken())
                    ->setUser($user)
                    ->setType($type)
                    ->setAttestEvent($event)
                    ->setChainId($event->getChainId())
                    ->setContract($event->getContract())
                    ->setOwnerAddress('0x' . substr($event->getTopic1(), 26, 40))
                    ->setIdInContractHex($event->getTopic2())
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