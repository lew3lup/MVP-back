<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Descriptionable extends Serializable
{
    //ToDo: вынести в конфиг
    public const LANGUAGES = ['en', 'ru'];
    public const DEFAULT_LANGUAGE = 'en';

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    protected $name;
    /**
     * @var array
     * @ORM\Column(type="json")
     */
    protected $description;

    /**
     * @return array
     */
    public function getName(): array
    {
        return $this->name;
    }

    /**
     * @param array $name
     * @return Descriptionable
     */
    public function setName(array $name): Descriptionable
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getDescription(): array
    {
        return $this->description;
    }

    /**
     * @param array $description
     * @return Descriptionable
     */
    public function setDescription(array $description): Descriptionable
    {
        $this->description = $description;
        return $this;
    }
}