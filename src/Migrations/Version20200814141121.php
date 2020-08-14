<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814141121 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_type_child DROP FOREIGN KEY FK_795DEDD6C51EFA73');
        $this->addSql('CREATE TABLE activity_theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, weight SMALLINT NOT NULL, is_numeric_notes TINYINT(1) NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE activity_type');
        $this->addSql('DROP INDEX IDX_795DEDD6C51EFA73 ON activity_type_child');
        $this->addSql('ALTER TABLE activity_type_child CHANGE activity_type_id activity_theme_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity_type_child ADD CONSTRAINT FK_795DEDD6F908C489 FOREIGN KEY (activity_theme_id) REFERENCES activity_theme (id)');
        $this->addSql('CREATE INDEX IDX_795DEDD6F908C489 ON activity_type_child (activity_theme_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_type_child DROP FOREIGN KEY FK_795DEDD6F908C489');
        $this->addSql('CREATE TABLE activity_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, weight SMALLINT NOT NULL, is_numeric_notes TINYINT(1) NOT NULL, display_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE activity_theme');
        $this->addSql('DROP INDEX IDX_795DEDD6F908C489 ON activity_type_child');
        $this->addSql('ALTER TABLE activity_type_child CHANGE activity_theme_id activity_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity_type_child ADD CONSTRAINT FK_795DEDD6C51EFA73 FOREIGN KEY (activity_type_id) REFERENCES activity_type (id)');
        $this->addSql('CREATE INDEX IDX_795DEDD6C51EFA73 ON activity_type_child (activity_type_id)');
    }
}
