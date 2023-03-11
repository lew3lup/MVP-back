<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311081744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        //IdNft
        $this->addSql('ALTER TABLE id_nfts
            ADD COLUMN user_id integer not null,
            ADD COLUMN id_in_contract integer not null,
            ADD COLUMN owner_address char (42) not null,
            ADD COLUMN event_id integer not null,
            ADD FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            ADD FOREIGN KEY (event_id) REFERENCES events ON DELETE CASCADE
        ');

        //BabtToken
        $this->addSql('ALTER TABLE babt_tokens ADD COLUMN owner_address char (42) not null');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE id_nfts
            DROP COLUMN user_id,
            DROP COLUMN id_in_contract,
            DROP COLUMN owner_address,
            DROP COLUMN event_id
        ');
        $this->addSql('ALTER TABLE babt_tokens DROP COLUMN owner_address');
    }
}
