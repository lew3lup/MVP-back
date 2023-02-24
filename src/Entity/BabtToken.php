<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность BABT-токена
 *
 * @ORM\Table(name="babt_tokens")
 * @ORM\Entity(repositoryClass="App\Repository\BabtTokenRepository")
 *
 * Class BabtToken
 * @package App\Entity
 */
class BabtToken
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="babtTokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * ID паспорта в рамках контракта
     *
     * @var int
     * @ORM\Column(type="integer", name="id_in_contract")
     */
    private $idInContract;
    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return BabtToken
     */
    public function setUser(User $user): BabtToken
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdInContract(): int
    {
        return $this->idInContract;
    }

    /**
     * @param int $idInContract
     * @return BabtToken
     */
    public function setIdInContract(int $idInContract): BabtToken
    {
        $this->idInContract = $idInContract;
        return $this;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     * @return BabtToken
     */
    public function setEvent(Event $event): BabtToken
    {
        $this->event = $event;
        return $this;
    }
}