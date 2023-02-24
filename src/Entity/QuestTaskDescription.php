<?php

namespace App\Entity;

/**
 * Сущность описания квестового задания на одном из языков
 *
 * @ORM\Table(name="quests_tasks_descriptions")
 * @ORM\Entity(repositoryClass="App\Repository\QuestTaskDescriptionRepository")
 *
 * Class QuestTaskDescription
 * @package App\Entity
 */
class QuestTaskDescription extends Description
{
    /**
     * @var QuestTask
     * @ORM\ManyToOne(targetEntity="QuestTask", inversedBy="descriptions")
     * @ORM\JoinColumn(name="quest_task_id", referencedColumnName="id")
     */
    private $questTask;

    /**
     * @return QuestTask
     */
    public function getQuestTask(): QuestTask
    {
        return $this->questTask;
    }

    /**
     * @param QuestTask $questTask
     * @return QuestTaskDescription
     */
    public function setQuestTask(QuestTask $questTask): QuestTaskDescription
    {
        $this->questTask = $questTask;
        return $this;
    }
}