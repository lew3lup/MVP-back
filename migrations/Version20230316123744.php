<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316123744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //SbtToken
        $this->addSql('ALTER TABLE sbt_tokens ADD COLUMN type smallint not null');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sbt_tokens DROP COLUMN type');
    }
}
