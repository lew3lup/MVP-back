<?php

namespace App\Service;

use App\Entity\Game;
use App\Exception\NotFoundException;
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
     * @param string $url
     * @param bool $active
     * @return Game
     */
    public function updateGame(Game $game, string $url, bool $active): Game
    {
        //ToDo: валидация $url
        return $game->setUrl($url)->setActive($active);
    }
}