<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220603124141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create|drop event_stream table';
    }

    public function up(Schema $schema): void
    {
        //$this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE event_streams (
              no BIGSERIAL,
              real_stream_name VARCHAR(150) NOT NULL,
              stream_name CHAR(41) NOT NULL,
              metadata JSONB,
              category VARCHAR(150),
              PRIMARY KEY (no),
              UNIQUE (stream_name)
            );
        ');
        $this->addSql('CREATE INDEX ON event_streams (category)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_streams');
    }
}
