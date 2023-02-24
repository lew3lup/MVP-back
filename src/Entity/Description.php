<?php

namespace App\Entity;

abstract class Description
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * Двухсимвольный код языка ISO 639-1
     *
     * @var string
     * @ORM\Column(type="string", length=2)
     */
    private $lang;
    /**
     * Название сущности на указанном языке
     *
     * @var string
     * @ORM\Column(type="text")
     */
    private $name;
    /**
     * Текстовое описание сущности на указанном языке
     *
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     * @return Description
     */
    public function setLang(string $lang): Description
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Description
     */
    public function setName(string $name): Description
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Description
     */
    public function setDescription(string $description): Description
    {
        $this->description = $description;
        return $this;
    }
}