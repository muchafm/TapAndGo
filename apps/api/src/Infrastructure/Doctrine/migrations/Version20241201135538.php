<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201135538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add bike and dock tables and update station table by adding a new property and dropping two properties.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bike (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', state ENUM(\'DISABLED\', \'ENABLED\', \'UNDER_REPAIR\') NOT NULL COMMENT \'(DC2Type:State)\', bike_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dock (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', station_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', dock_number INT NOT NULL, state ENUM(\'DISABLED\', \'ENABLED\', \'UNDER_REPAIR\') NOT NULL COMMENT \'(DC2Type:State)\', INDEX IDX_423BB3E121BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dock ADD CONSTRAINT FK_423BB3E121BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE station ADD capacity INT NOT NULL, DROP total_stands, DROP available_bikes, DROP number');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dock DROP FOREIGN KEY FK_423BB3E121BDB235');
        $this->addSql('DROP TABLE bike');
        $this->addSql('DROP TABLE dock');
        $this->addSql('ALTER TABLE station ADD available_bikes INT NOT NULL, ADD number INT NOT NULL, CHANGE capacity total_stands INT NOT NULL');
    }
}
