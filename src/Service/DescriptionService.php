<?php

namespace App\Service;

use App\Entity\Description;
use App\Entity\Game;
use App\Entity\GameDescription;
use Doctrine\ORM\EntityManagerInterface;

class DescriptionService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * DescriptionService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Game $game
     * @param string $lang
     * @param string $name
     * @param string $description
     * @return GameDescription
     */
    public function addGameDescription(
        Game $game,
        string $lang,
        string $name,
        string $description
    ): GameDescription {
        /** @var GameDescription $description */
        $description = $this->addDescription(
            (new GameDescription())->setGame($game),
            $lang,
            $name,
            $description
        );
        return $description;
    }

    /**
     * @param Description $descriptionEntity
     * @param string $lang
     * @param string $name
     * @param string $description
     * @return Description
     */
    private function addDescription(
        Description $descriptionEntity,
        string $lang,
        string $name,
        string $description
    ): Description {
        //ToDo: проверять $lang на соответствие допустимым вариантам, а также $name и $description на непустоту
        $descriptionEntity->setLang($lang)->setName($name)->setDescription($description);
        $this->em->persist($descriptionEntity);
        return $descriptionEntity;
    }
}