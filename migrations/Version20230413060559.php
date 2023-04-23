<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230413060559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //GameDescription
        $this->addSql('DROP TABLE games_descriptions');

        //AchievementDescription
        $this->addSql('DROP TABLE achievements_descriptions');

        //QuestDescription
        $this->addSql('DROP TABLE quests_descriptions');

        //QuestTaskDescription
        $this->addSql('DROP TABLE quests_tasks_descriptions');

        //Game
        $this->addSql('TRUNCATE TABLE games CASCADE');
        $this->addSql('ALTER TABLE games RENAME COLUMN url to home_page');
        $this->addSql('ALTER TABLE games
            ADD COLUMN logo text default null,
            ADD COLUMN path text not null,
            ADD COLUMN twitter text default null,
            ADD COLUMN discord text default null,
            ADD COLUMN telegram text default null,
            ADD COLUMN coin_market_cap text default null,
            ADD COLUMN name json not null,
            ADD COLUMN description json not null,
            ADD UNIQUE (path)
        ');

        //Achievement
        $this->addSql('ALTER TABLE achievements
            ADD COLUMN name json not null,
            ADD COLUMN description json not null
        ');

        //Quest
        $this->addSql('ALTER TABLE quests
            ADD COLUMN name json not null,
            ADD COLUMN description json not null
        ');

        //QuestTask
        $this->addSql('ALTER TABLE quests_tasks
            ADD COLUMN name json not null,
            ADD COLUMN description json not null
        ');

        //Category
        $this->addSql('CREATE TABLE categories (
            id serial not null,
            name json not null,
            description json not null,
            PRIMARY KEY (id)
        )');

        //GameCategory
        $this->addSql('CREATE TABLE games_categories (
            id serial not null,
            game_id integer not null,
            category_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories ON DELETE CASCADE
        )');

        //Chain
        $this->addSql('CREATE TABLE chains (
            id serial not null,
            name text not null,
            short_name text default null,
            PRIMARY KEY (id),
            UNIQUE (name),
            UNIQUE (short_name)
        )');

        //GameChain
        $this->addSql('CREATE TABLE games_chains (
            id serial not null,
            game_id integer not null,
            chain_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE,
            FOREIGN KEY (chain_id) REFERENCES chains ON DELETE CASCADE
        )');

        //Backer
        $this->addSql('CREATE TABLE backers (
            id serial not null,
            name text not null,
            PRIMARY KEY (id),
            UNIQUE (name)
        )');

        //GameBacker
        $this->addSql('CREATE TABLE games_backers (
            id serial not null,
            game_id integer not null,
            backer_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE,
            FOREIGN KEY (backer_id) REFERENCES backers ON DELETE CASCADE
        )');

        //Image
        $this->addSql('CREATE TABLE images (
            id serial not null,
            game_id integer not null,
            url text not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE,
            UNIQUE (url)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE games
            DROP COLUMN logo,
            DROP COLUMN path,
            DROP COLUMN twitter,
            DROP COLUMN discord,
            DROP COLUMN telegram,
            DROP COLUMN coin_market_cap,
            DROP COLUMN name,
            DROP COLUMN description
        ');
        $this->addSql('ALTER TABLE games RENAME COLUMN home_page to url');
        $this->addSql('ALTER TABLE achievements
            DROP COLUMN name,
            DROP COLUMN description
        ');
        $this->addSql('ALTER TABLE quests
            DROP COLUMN name,
            DROP COLUMN description
        ');
        $this->addSql('ALTER TABLE quests_tasks
            DROP COLUMN name,
            DROP COLUMN description
        ');
        $this->addSql('DROP TABLE games_categories');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE games_chains');
        $this->addSql('DROP TABLE chains');
        $this->addSql('DROP TABLE games_backers');
        $this->addSql('DROP TABLE backers');
        $this->addSql('DROP TABLE images');
        $this->addSql('CREATE TABLE games_descriptions (
            id serial not null,
            lang char(2) not null,
            name text not null,
            description text not null,
            game_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE,
            UNIQUE (lang, game_id)
        )');
        $this->addSql('CREATE TABLE achievements_descriptions (
            id serial not null,
            lang char(2) not null,
            name text not null,
            description text not null,
            achievement_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (achievement_id) REFERENCES achievements ON DELETE CASCADE,
            UNIQUE (lang, achievement_id)
        )');
        $this->addSql('CREATE TABLE quests_descriptions (
            id serial not null,
            lang char(2) not null,
            name text not null,
            description text not null,
            quest_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (quest_id) REFERENCES quests ON DELETE CASCADE,
            UNIQUE (lang, quest_id)
        )');
        $this->addSql('CREATE TABLE quests_tasks_descriptions (
            id serial not null,
            lang char(2) not null,
            name text not null,
            description text not null,
            quest_task_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (quest_task_id) REFERENCES quests ON DELETE CASCADE,
            UNIQUE (lang, quest_task_id)
        )');
    }
}
