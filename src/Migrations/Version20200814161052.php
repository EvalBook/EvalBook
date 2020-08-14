<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814161052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE knowledge_type DROP FOREIGN KEY FK_E3EB1D1D114F2C23');
        $this->addSql('CREATE TABLE activity_theme_domain (id INT AUTO_INCREMENT NOT NULL, activity_theme_id INT NOT NULL, classroom_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, type VARCHAR(100) NOT NULL, INDEX IDX_7E29E836F908C489 (activity_theme_id), INDEX IDX_7E29E8366278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_theme_domain ADD CONSTRAINT FK_7E29E836F908C489 FOREIGN KEY (activity_theme_id) REFERENCES activity_theme (id)');
        $this->addSql('ALTER TABLE activity_theme_domain ADD CONSTRAINT FK_7E29E8366278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('DROP TABLE activity_type_child');
        $this->addSql('DROP INDEX IDX_E3EB1D1D114F2C23 ON knowledge_type');
        $this->addSql('ALTER TABLE knowledge_type CHANGE activity_type_child_id activity_theme_domain_id INT NOT NULL');
        $this->addSql('ALTER TABLE knowledge_type ADD CONSTRAINT FK_E3EB1D1D92B2BDE4 FOREIGN KEY (activity_theme_domain_id) REFERENCES activity_theme_domain (id)');
        $this->addSql('CREATE INDEX IDX_E3EB1D1D92B2BDE4 ON knowledge_type (activity_theme_domain_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE knowledge_type DROP FOREIGN KEY FK_E3EB1D1D92B2BDE4');
        $this->addSql('CREATE TABLE activity_type_child (id INT AUTO_INCREMENT NOT NULL, activity_theme_id INT NOT NULL, classroom_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, display_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_795DEDD6F908C489 (activity_theme_id), INDEX IDX_795DEDD66278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity_type_child ADD CONSTRAINT FK_795DEDD66278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE activity_type_child ADD CONSTRAINT FK_795DEDD6F908C489 FOREIGN KEY (activity_theme_id) REFERENCES activity_theme (id)');
        $this->addSql('DROP TABLE activity_theme_domain');
        $this->addSql('DROP INDEX IDX_E3EB1D1D92B2BDE4 ON knowledge_type');
        $this->addSql('ALTER TABLE knowledge_type CHANGE activity_theme_domain_id activity_type_child_id INT NOT NULL');
        $this->addSql('ALTER TABLE knowledge_type ADD CONSTRAINT FK_E3EB1D1D114F2C23 FOREIGN KEY (activity_type_child_id) REFERENCES activity_type_child (id)');
        $this->addSql('CREATE INDEX IDX_E3EB1D1D114F2C23 ON knowledge_type (activity_type_child_id)');
    }
}
