<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920075832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE school_report_theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, release_date DATE NOT NULL, uuid VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE implantation ADD school_report_theme_id INT NOT NULL');
        $this->addSql('ALTER TABLE implantation ADD CONSTRAINT FK_16DC605C7C73556 FOREIGN KEY (school_report_theme_id) REFERENCES school_report_theme (id)');
        $this->addSql('CREATE INDEX IDX_16DC605C7C73556 ON implantation (school_report_theme_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implantation DROP FOREIGN KEY FK_16DC605C7C73556');
        $this->addSql('DROP TABLE school_report_theme');
        $this->addSql('DROP INDEX IDX_16DC605C7C73556 ON implantation');
        $this->addSql('ALTER TABLE implantation DROP school_report_theme_id');
    }
}
