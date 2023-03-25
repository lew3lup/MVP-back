<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Description extends SerializableEntity
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * Двухсимвольный код языка ISO 639-1
     *
     * @var string
     * @ORM\Column(type="string", length=2)
     */
    protected $lang;
    /**
     * Название сущности на указанном языке
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $name;
    /**
     * Текстовое описание сущности на указанном языке
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $description;

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

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'            => $this->id,
            'lang'          => $this->lang,
            'name'          => $this->name,
            'description'   => $this->description,
        ];
    }
}