<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222140736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //Event
        $this->addSql('CREATE TABLE events (
            id serial not null,
            chain_id integer not null,
            contract char (42) not null,
            transaction_hash char (66) not null,
            name char (66) not null,
            topic_1 char (66) null,
            topic_2 char (66) null,
            topic_3 char (66) null,
            data text null,
            block_number integer not null,
            timestamp timestamp(0) not null,
            parsed_at timestamp(0) default now() not null,
            PRIMARY KEY (id),
            UNIQUE (chain_id, transaction_hash, name, topic_1, topic_2, topic_3, data)
        )');
        $this->addSql('CREATE INDEX events_chains ON events (chain_id)');
        $this->addSql('CREATE INDEX events_transactions ON events (transaction_hash)');
        $this->addSql('CREATE INDEX events_blocks ON events (block_number)');

        //User
        $this->addSql('CREATE TABLE users (
            id serial not null,
            address char (42) null,
            email text null,
            name text null,
            registered_at timestamp(0) default now() not null,
            PRIMARY KEY (id),
            UNIQUE (address),
            UNIQUE (email)
        )');

        //Game
        $this->addSql('CREATE TABLE games (
            id serial not null,
            url text not null,
            PRIMARY KEY (id)
        )');

        //GameDescription
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

        //GameAdmin
        $this->addSql('CREATE TABLE games_admins (
            id serial not null,
            game_id integer not null,
            user_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE
        )');

        //BabtToken
        $this->addSql('CREATE TABLE babt_tokens (
            id serial not null,
            user_id integer not null,
            id_in_contract integer not null,
            event_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            FOREIGN KEY (event_id) REFERENCES events ON DELETE CASCADE
        )');

        //Achievement
        $this->addSql('CREATE TABLE achievements (
            id serial not null,
            PRIMARY KEY (id)
        )');

        //AchievementDescription
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

        //UserAchievement
        $this->addSql('CREATE TABLE users_achievements (
            id serial not null,
            user_id integer not null,
            achievement_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            FOREIGN KEY (achievement_id) REFERENCES achievements ON DELETE CASCADE
        )');

        //LastParsedBlock
        $this->addSql('CREATE TABLE last_parsed_blocks (
            id serial not null,
            chain_id integer not null,
            contract char (42) not null,
            block integer,
            updated_at timestamp(0) default now() not null,
            PRIMARY KEY (id),
            UNIQUE (chain_id, contract)
        )');

        //Quest
        $this->addSql('CREATE TABLE quests (
            id serial not null,
            game_id integer not null,
            type integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (game_id) REFERENCES games ON DELETE CASCADE
        )');
        $this->addSql('CREATE INDEX quests_types ON quests (type)');

        //QuestDescription
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

        //UserQuest
        $this->addSql('CREATE TABLE users_quests (
            id serial not null,
            user_id integer not null,
            quest_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            FOREIGN KEY (quest_id) REFERENCES quests ON DELETE CASCADE
        )');

        //QuestTask
        $this->addSql('CREATE TABLE quests_tasks (
            id serial not null,
            quest_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (quest_id) REFERENCES quests ON DELETE CASCADE
        )');

        //QuestTaskDescription
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

        //UserQuestTask
        $this->addSql('CREATE TABLE users_quests_tasks (
            id serial not null,
            user_id integer not null,
            quest_task_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            FOREIGN KEY (quest_task_id) REFERENCES quests_tasks ON DELETE CASCADE
        )');

        //UserReferral
        $this->addSql('CREATE TABLE users_referrals (
            id serial not null,
            user_id integer not null,
            referral_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            FOREIGN KEY (referral_id) REFERENCES users ON DELETE CASCADE
        )');

        //IdNft
        $this->addSql('CREATE TABLE id_nfts (
            id serial not null,
            PRIMARY KEY (id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE id_nfts');
        $this->addSql('DROP TABLE users_referrals');
        $this->addSql('DROP TABLE users_quests_tasks');
        $this->addSql('DROP TABLE quests_tasks_descriptions');
        $this->addSql('DROP TABLE quests_tasks');
        $this->addSql('DROP TABLE users_quests');
        $this->addSql('DROP TABLE quests_descriptions');
        $this->addSql('DROP TABLE quests');
        $this->addSql('DROP TABLE last_parsed_blocks');
        $this->addSql('DROP TABLE users_achievements');
        $this->addSql('DROP TABLE achievements_descriptions');
        $this->addSql('DROP TABLE achievements');
        $this->addSql('DROP TABLE babt_tokens');
        $this->addSql('DROP TABLE games_admins');
        $this->addSql('DROP TABLE games_descriptions');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE events');
    }
}
