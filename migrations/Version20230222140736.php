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
            chain_id smallint not null,
            contract varchar (42) not null,
            transaction_hash varchar (66) not null,
            name varchar (66) not null,
            topic_1 varchar (66) null,
            topic_2 varchar (66) null,
            topic_3 varchar (66) null,
            data text null,
            block_number integer not null,
            timestamp timestamp(0) not null,
            parsed_at timestamp(0) default now() not null,
            PRIMARY KEY (id),
            UNIQUE (chain_id, transaction_hash, name)
        )');
        $this->addSql('CREATE INDEX events_chain_ids ON events (chain_id)');
        $this->addSql('CREATE INDEX events_transactions_hashes ON events (transaction_hash)');
        $this->addSql('CREATE INDEX events_block_numbers ON events (block_number)');

        //User
        $this->addSql('CREATE TABLE users (
            id serial not null,
            address varchar (42) null,
            registered_at timestamp(0) default now() not null,
            PRIMARY KEY (id),
            UNIQUE (address)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE users');
    }
}
