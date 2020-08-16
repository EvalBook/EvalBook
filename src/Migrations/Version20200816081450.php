<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200816081450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_theme_domain_skill CHANGE user user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_theme_domain_skill ADD CONSTRAINT FK_9DD7C5ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9DD7C5ABA76ED395 ON activity_theme_domain_skill (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_theme_domain_skill DROP FOREIGN KEY FK_9DD7C5ABA76ED395');
        $this->addSql('DROP INDEX IDX_9DD7C5ABA76ED395 ON activity_theme_domain_skill');
        $this->addSql('ALTER TABLE activity_theme_domain_skill CHANGE user_id user INT DEFAULT NULL');
    }
}
