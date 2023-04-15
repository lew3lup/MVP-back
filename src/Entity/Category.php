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