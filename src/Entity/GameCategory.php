<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность связи игры и категории
 *
 * @ORM\Table(name="games_categories")
 * @ORM\Entity(repositoryClass="App\Repository\GameCategoryRepository")
 *
 * Class GameCategory
 * @package App\Entity
 */
class GameCategory
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="gameDescriptions")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;
    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="gameCategories")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     * @return GameCategory
     */
    public function setGame(Game $game): GameCategory
    {
        $this->game = $game;
        $game->addGameCategory($this);
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return GameCategory
     */
    public function setCategory(Category $category): GameCategory
    {
        $this->category = $category;
        return $this;
    }
}