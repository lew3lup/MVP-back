<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность описания категории на одном из языков
 *
 * @ORM\Table(name="categories_descriptions")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryDescriptionRepository")
 *
 * Class CategoryDescription
 * @package App\Entity
 */
class CategoryDescription extends Description
{
    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="descriptions")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return CategoryDescription
     */
    public function setCategory(Category $category): CategoryDescription
    {
        $this->category = $category;
        return $this;
    }
}