<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @var CategoryDescription[]
     * @ORM\OneToMany(targetEntity="CategoryDescription", mappedBy="quest")
     */
    private $descriptions;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->descriptions = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'descriptions'  => $this->descriptions->toArray(),
        ];
    }
}