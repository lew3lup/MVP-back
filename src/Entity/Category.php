<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность категории
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 *
 * Class Category
 * @package App\Entity
 */
class Category extends Descriptionable
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var GameCategory[]
     * @ORM\OneToMany(targetEntity="GameCategory", mappedBy="category")
     */
    private $gameCategories;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->gameCategories = new ArrayCollection();
    }

    /**
     * @return GameCategory[]|Collection
     */
    public function getGameCategories(): Collection
    {
        return $this->gameCategories;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
        ];
    }
}