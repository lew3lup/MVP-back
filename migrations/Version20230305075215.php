<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305075215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //UserFractal
        $this->addSql('CREATE TABLE users_fractals (
            id serial not null,
            user_id integer not null,
            access_data json not null,
            uid text not null,
            verification_cases json not null,
            status smallint not null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users_fractals');
    }
}
