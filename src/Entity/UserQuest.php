<?php

namespace App\Entity;

/**
 * Сущность, отражающая участие пользователя в квесте
 *
 * @ORM\Table(name="users_quests")
 * @ORM\Entity(repositoryClass="App\Repository\UserQuestRepository")
 *
 * Class UserQuest
 * @package App\Entity
 */
class UserQuest
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userQuests")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var Quest
     * @ORM\ManyToOne(targetEntity="Quest", inversedBy="userQuests")
     * @ORM\JoinColumn(name="quest_id", referencedColumnName="id")
     */
    private $quest;

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
     * @return UserQuest
     */
    public function setUser(User $user): UserQuest
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }

    /**
     * @param Quest $quest
     * @return UserQuest
     */
    public function setQuest(Quest $quest): UserQuest
    {
        $this->quest = $quest;
        return $this;
    }
}