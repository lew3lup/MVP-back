<?php

namespace App\Entity;

/**
 * Сущность NFT LEW3L-UP ID
 *
 * @ORM\Table(name=id_nfts")
 * @ORM\Entity(repositoryClass="App\Repository\IdNftRepository")
 *
 * Class IdNft
 * @package App\Entity
 */
class IdNft
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    //ToDo

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}