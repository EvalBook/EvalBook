<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200920145127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implantation ADD school_report_theme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE implantation ADD CONSTRAINT FK_16DC605C7C73556 FOREIGN KEY (school_report_theme_id) REFERENCES school_report_theme (id)');
        $this->addSql('CREATE INDEX IDX_16DC605C7C73556 ON implantation (school_report_theme_id)');
        $this->addSql('ALTER TABLE note_type DROP coefficient');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implantation DROP FOREIGN KEY FK_16DC605C7C73556');
        $this->addSql('DROP INDEX IDX_16DC605C7C73556 ON implantation');
        $this->addSql('ALTER TABLE implantation DROP school_report_theme_id');
        $this->addSql('ALTER TABLE note_type ADD coefficient INT NOT NULL');
    }
}
