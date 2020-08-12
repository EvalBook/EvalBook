<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200810153115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE knowledge_type DROP FOREIGN KEY FK_E3EB1D1DC51EFA73');
        $this->addSql('DROP INDEX IDX_E3EB1D1DC51EFA73 ON knowledge_type');
        $this->addSql('ALTER TABLE knowledge_type CHANGE activity_type_id activity_type_child_id INT NOT NULL');
        $this->addSql('ALTER TABLE knowledge_type ADD CONSTRAINT FK_E3EB1D1D114F2C23 FOREIGN KEY (activity_type_child_id) REFERENCES activity_type_child (id)');
        $this->addSql('CREATE INDEX IDX_E3EB1D1D114F2C23 ON knowledge_type (activity_type_child_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE knowledge_type DROP FOREIGN KEY FK_E3EB1D1D114F2C23');
        $this->addSql('DROP INDEX IDX_E3EB1D1D114F2C23 ON knowledge_type');
        $this->addSql('ALTER TABLE knowledge_type CHANGE activity_type_child_id activity_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE knowledge_type ADD CONSTRAINT FK_E3EB1D1DC51EFA73 FOREIGN KEY (activity_type_id) REFERENCES activity_type (id)');
        $this->addSql('CREATE INDEX IDX_E3EB1D1DC51EFA73 ON knowledge_type (activity_type_id)');
    }
}
