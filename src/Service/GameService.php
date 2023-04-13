<?php

namespace App\Service;

use App\Entity\Game;
use App\Exception\NotFoundException;
use App\Exception\BadRequestException;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var GameRepository */
    private $gameRepo;

    /**
     * GameService constructor.
     * @param EntityManagerInterface $em
     * @param GameRepository $gameRepo
     */
    public function __construct(EntityManagerInterface $em, GameRepository $gameRepo)
    {
        $this->em = $em;
        $this->gameRepo = $gameRepo;
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return Game
     */
    public function getByIdAndAdminId(int $id, int $adminId): Game
    {
        $game = $this->gameRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$game) {
            throw new NotFoundException();
        }
        return $game;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function addGame(Game $game, array $data): Game
    {
        $game = $this->setData($game, $data)->setAddedAt(new DateTimeImmutable());
        $this->em->persist($game);
        return $game;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function updateGame(Game $game, array $data): Game
    {
        if (!isset($data['active'])) {
            throw new BadRequestException();
        }
        return $this->setData($game, $data)->setActive($data['active']);
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    private function setData(Game $game, array $data): Game
    {
        //ToDo: детальные проверки ссылок
        if (
            empty($data['homePage']) || !filter_var($data['url'], FILTER_VALIDATE_URL) ||
            empty($data['path']) || strlen($data['path']) > 30 ||
            (!empty($data['twitter']) && !filter_var($data['twitter'], FILTER_VALIDATE_URL)) ||
            (!empty($data['discord']) && !filter_var($data['discord'], FILTER_VALIDATE_URL)) ||
            (!empty($data['telegram']) && !filter_var($data['telegram'], FILTER_VALIDATE_URL)) ||
            (!empty($data['coinMarketCap']) && !filter_var($data['coinMarketCap'], FILTER_VALIDATE_URL)) ||
            !isset($data['active'])
        ) {
            throw new BadRequestException();
        }
        return $game
            ->setPath($data['path'])
            ->setHomePage($data['homePage'])
            ->setTwitter(!empty($data['twitter']) ? $data['twitter'] : null)
            ->setDiscord(!empty($data['discord']) ? $data['discord'] : null)
            ->setTelegram(!empty($data['telegram']) ? $data['telegram'] : null)
            ->setCoinMarketCap(!empty($data['coinMarketCap']) ? $data['coinMarketCap'] : null)
            ->setActive($data['active'])
        ;
    }
}