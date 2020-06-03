<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603112910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE student_contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(100) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_contact_relation (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, student_id INT NOT NULL, relation VARCHAR(255) NOT NULL, send_school_report TINYINT(1) NOT NULL, INDEX IDX_2AFDFAABE7A1254A (contact_id), INDEX IDX_2AFDFAABCB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_contact_relation ADD CONSTRAINT FK_2AFDFAABE7A1254A FOREIGN KEY (contact_id) REFERENCES student_contact (id)');
        $this->addSql('ALTER TABLE student_contact_relation ADD CONSTRAINT FK_2AFDFAABCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE user_configuration DROP use_implantations');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student_contact_relation DROP FOREIGN KEY FK_2AFDFAABE7A1254A');
        $this->addSql('DROP TABLE student_contact');
        $this->addSql('DROP TABLE student_contact_relation');
        $this->addSql('ALTER TABLE user_configuration ADD use_implantations TINYINT(1) DEFAULT NULL');
    }
}
