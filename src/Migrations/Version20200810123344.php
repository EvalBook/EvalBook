<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810123344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_type_child ADD classroom_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_type_child ADD CONSTRAINT FK_795DEDD66278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('CREATE INDEX IDX_795DEDD66278D5A8 ON activity_type_child (classroom_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity_type_child DROP FOREIGN KEY FK_795DEDD66278D5A8');
        $this->addSql('DROP INDEX IDX_795DEDD66278D5A8 ON activity_type_child');
        $this->addSql('ALTER TABLE activity_type_child DROP classroom_id');
    }
}
