<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность, отражающая выполнение пользователем квестового задания
 *
 * @ORM\Table(name="users_quests_tasks")
 * @ORM\Entity(repositoryClass="App\Repository\UserQuestTaskRepository")
 *
 * Class UserQuestTask
 * @package App\Entity
 */
class UserQuestTask
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userQuestTasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var QuestTask
     * @ORM\ManyToOne(targetEntity="QuestTask", inversedBy="userQuestTasks")
     * @ORM\JoinColumn(name="quest_task_id", referencedColumnName="id")
     */
    private $questTask;

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
     * @return UserQuestTask
     */
    public function setUser(User $user): UserQuestTask
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return QuestTask
     */
    public function getQuestTask(): QuestTask
    {
        return $this->questTask;
    }

    /**
     * @param QuestTask $questTask
     * @return UserQuestTask
     */
    public function setQuestTask(QuestTask $questTask): UserQuestTask
    {
        $this->questTask = $questTask;
        return $this;
    }
}