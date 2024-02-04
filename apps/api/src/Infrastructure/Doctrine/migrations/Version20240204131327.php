<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240204131327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add city and station relationship';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE station ADD city_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B18BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_9F39F8B18BAC62AF ON station (city_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B18BAC62AF');
        $this->addSql('DROP INDEX IDX_9F39F8B18BAC62AF ON station');
        $this->addSql('ALTER TABLE station DROP city_id');
    }
}
