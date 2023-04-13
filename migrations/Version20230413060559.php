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
        //Game
        $this->addSql('TRUNCATE TABLE games CASCADE');
        $this->addSql('ALTER TABLE games RENAME COLUMN url to home_page');
        $this->addSql('ALTER TABLE games
            ADD COLUMN path text not null,
            ADD COLUMN twitter text default null,
            ADD COLUMN discord text default null,
            ADD COLUMN telegram text default null
        ');

        //Category
        $this->addSql('CREATE TABLE categories (
            id serial not null,
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

        //CategoryDescription
        $this->addSql('CREATE TABLE categories_descriptions (
            id serial not null,
            lang char(2) not null,
            name text not null,
            description text not null,
            category_id integer not null,
            PRIMARY KEY (id),
            FOREIGN KEY (category_id) REFERENCES categories ON DELETE CASCADE,
            UNIQUE (lang, category_id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE games
            DROP COLUMN path,
            DROP COLUMN twitter,
            DROP COLUMN discord,
            DROP COLUMN telegram
        ');
        $this->addSql('ALTER TABLE games RENAME COLUMN home_page to url');
        $this->addSql('DROP TABLE games_categories');
        $this->addSql('DROP TABLE categories_descriptions');
        $this->addSql('DROP TABLE categories');
    }
}
