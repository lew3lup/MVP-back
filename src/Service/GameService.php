<?php

namespace App\Service;

use App\Entity\Game;
use App\Exception\NotFoundException;
use App\Exception\BadRequestException;
use App\Repository\GameRepository;

class GameService
{
    /** @var GameRepository */
    private $gameRepo;

    /**
     * GameService constructor.
     * @param GameRepository $gameRepo
     */
    public function __construct(GameRepository $gameRepo)
    {
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
    public function updateGame(Game $game, array $data): Game
    {
        if (empty($data['url']) || !isset($data['active']) || !filter_var($data['url'], FILTER_VALIDATE_URL)) {
            throw new BadRequestException();
        }
        return $game->setHomePage($data['url'])->setActive($data['active']);
    }
}