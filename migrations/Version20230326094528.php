<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326094528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //Game
        $this->addSql('ALTER TABLE games
            ADD COLUMN active boolean default true not null,
            ADD COLUMN deleted boolean default false not null,
            ADD COLUMN added_at timestamp(0) default now() not null,
            ADD COLUMN deleted_at timestamp(0) default null
        ');

        //Quest
        $this->addSql('ALTER TABLE quests
            ADD COLUMN active boolean default true not null,
            ADD COLUMN deleted boolean default false not null,
            ADD COLUMN added_at timestamp(0) default now() not null,
            ADD COLUMN deleted_at timestamp(0) default null
        ');

        //QuestTask
        $this->addSql('ALTER TABLE quests_tasks
            ADD COLUMN active boolean default true not null,
            ADD COLUMN deleted boolean default false not null,
            ADD COLUMN added_at timestamp(0) default now() not null,
            ADD COLUMN deleted_at timestamp(0) default null
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE games
            DROP COLUMN active,
            DROP COLUMN deleted,
            DROP COLUMN added_at,
            DROP COLUMN deleted_at
        ');
        $this->addSql('ALTER TABLE quests
            DROP COLUMN active,
            DROP COLUMN deleted,
            DROP COLUMN added_at,
            DROP COLUMN deleted_at
        ');
        $this->addSql('ALTER TABLE quests_tasks
            DROP COLUMN active,
            DROP COLUMN deleted,
            DROP COLUMN added_at,
            DROP COLUMN deleted_at
        ');
    }
}
