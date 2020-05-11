<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511173127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96A10273AA');
        $this->addSql('DROP INDEX UNIQ_8F87BF96A10273AA ON classe');
        $this->addSql('ALTER TABLE classe DROP titulaire_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classe ADD titulaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96A10273AA FOREIGN KEY (titulaire_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F87BF96A10273AA ON classe (titulaire_id)');
    }
}
