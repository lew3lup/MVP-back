<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\GameAdmin;
use App\Entity\User;
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
    /** @var DescriptionService */
    private $descriptionService;

    /**
     * GameService constructor.
     * @param EntityManagerInterface $em
     * @param GameRepository $gameRepo
     * @param DescriptionService $descriptionService
     */
    public function __construct(
        EntityManagerInterface $em,
        GameRepository $gameRepo,
        DescriptionService $descriptionService
    ) {
        $this->em = $em;
        $this->gameRepo = $gameRepo;
        $this->descriptionService = $descriptionService;
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
     * @param User $user
     * @param array $data
     * @return Game
     */
    public function addGame(User $user, array $data): Game
    {
        $game = $this->setData(new Game(), $data)->setAddedAt(new DateTimeImmutable());
        $gameAdmin = (new GameAdmin())->setGame($game)->setUser($user);
        $this->em->persist($game);
        $this->em->persist($gameAdmin);
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
        $this->descriptionService->setData($game, $data);
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