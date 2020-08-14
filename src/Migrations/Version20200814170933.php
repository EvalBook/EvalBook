<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814170933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095ABA0BA21C');
        $this->addSql('CREATE TABLE activity_theme_domain_skill (id INT AUTO_INCREMENT NOT NULL, note_type_id INT NOT NULL, activity_theme_domain_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_9DD7C5AB44EA4809 (note_type_id), INDEX IDX_9DD7C5AB92B2BDE4 (activity_theme_domain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_theme_domain_skill ADD CONSTRAINT FK_9DD7C5AB44EA4809 FOREIGN KEY (note_type_id) REFERENCES note_type (id)');
        $this->addSql('ALTER TABLE activity_theme_domain_skill ADD CONSTRAINT FK_9DD7C5AB92B2BDE4 FOREIGN KEY (activity_theme_domain_id) REFERENCES activity_theme_domain (id)');
        $this->addSql('DROP TABLE knowledge_type');
        $this->addSql('DROP INDEX IDX_AC74095ABA0BA21C ON activity');
        $this->addSql('ALTER TABLE activity CHANGE knowledge_type_id activity_theme_domain_skill_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A1D561350 FOREIGN KEY (activity_theme_domain_skill_id) REFERENCES activity_theme_domain_skill (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A1D561350 ON activity (activity_theme_domain_skill_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A1D561350');
        $this->addSql('CREATE TABLE knowledge_type (id INT AUTO_INCREMENT NOT NULL, activity_theme_domain_id INT NOT NULL, note_type_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E3EB1D1D92B2BDE4 (activity_theme_domain_id), INDEX IDX_E3EB1D1D44EA4809 (note_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE knowledge_type ADD CONSTRAINT FK_E3EB1D1D44EA4809 FOREIGN KEY (note_type_id) REFERENCES note_type (id)');
        $this->addSql('ALTER TABLE knowledge_type ADD CONSTRAINT FK_E3EB1D1D92B2BDE4 FOREIGN KEY (activity_theme_domain_id) REFERENCES activity_theme_domain (id)');
        $this->addSql('DROP TABLE activity_theme_domain_skill');
        $this->addSql('DROP INDEX IDX_AC74095A1D561350 ON activity');
        $this->addSql('ALTER TABLE activity CHANGE activity_theme_domain_skill_id knowledge_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095ABA0BA21C FOREIGN KEY (knowledge_type_id) REFERENCES knowledge_type (id)');
        $this->addSql('CREATE INDEX IDX_AC74095ABA0BA21C ON activity (knowledge_type_id)');
    }
}
