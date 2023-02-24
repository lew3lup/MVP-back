<?php

namespace App\Entity;

/**
 * Сущность описания квеста на одном из языков
 *
 * @ORM\Table(name="quests_descriptions")
 * @ORM\Entity(repositoryClass="App\Repository\QuestDescriptionRepository")
 *
 * Class QuestDescription
 * @package App\Entity
 */
class QuestDescription extends Description
{
    /**
     * @var Quest
     * @ORM\ManyToOne(targetEntity="Quest", inversedBy="descriptions")
     * @ORM\JoinColumn(name="quest_id", referencedColumnName="id")
     */
    private $quest;

    /**
     * @return Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }

    /**
     * @param Quest $quest
     * @return QuestDescription
     */
    public function setQuest(Quest $quest): QuestDescription
    {
        $this->quest = $quest;
        return $this;
    }
}