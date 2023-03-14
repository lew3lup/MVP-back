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
        $this->addSql('DROP TABLE id_nfts');
        $this->addSql('DROP TABLE babt_tokens');

        //SbtToken
        $this->addSql('CREATE TABLE sbt_tokens (
            id serial not null,
            user_id integer not null,
            chain_id integer not null,
            contract char (42) not null,
            id_in_contract integer not null,
            id_in_contract_hex char(66) not null,
            owner_address char (42) not null,
            attest_event_id integer not null,
            revoke_event_id integer null,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users ON DELETE CASCADE,
            FOREIGN KEY (attest_event_id) REFERENCES events ON DELETE RESTRICT,
            FOREIGN KEY (revoke_event_id) REFERENCES events ON DELETE RESTRICT,
            UNIQUE (chain_id, contract, id_in_contract)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sbt_tokens');

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

        //IdNft
        $this->addSql('CREATE TABLE id_nfts (
            id serial not null,
            PRIMARY KEY (id)
        )');
    }
}
