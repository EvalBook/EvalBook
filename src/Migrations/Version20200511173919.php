<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511173919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classe ADD titulaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96A10273AA FOREIGN KEY (titulaire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8F87BF96A10273AA ON classe (titulaire_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DEBE7ADB');
        $this->addSql('DROP INDEX IDX_8D93D649DEBE7ADB ON user');
        $this->addSql('ALTER TABLE user DROP classes_titulaire_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96A10273AA');
        $this->addSql('DROP INDEX IDX_8F87BF96A10273AA ON classe');
        $this->addSql('ALTER TABLE classe DROP titulaire_id');
        $this->addSql('ALTER TABLE user ADD classes_titulaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DEBE7ADB FOREIGN KEY (classes_titulaire_id) REFERENCES classe (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DEBE7ADB ON user (classes_titulaire_id)');
    }
}
