<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619015245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, note_type_id INT NOT NULL, user_id INT DEFAULT NULL, period_id INT DEFAULT NULL, classroom_id INT DEFAULT NULL, date_added DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_AC74095A44EA4809 (note_type_id), INDEX IDX_AC74095AA76ED395 (user_id), INDEX IDX_AC74095AEC8B7ADE (period_id), INDEX IDX_AC74095A6278D5A8 (classroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, implantation_id INT NOT NULL, name VARCHAR(45) NOT NULL, INDEX IDX_497D309D7E3C61F9 (owner_id), INDEX IDX_497D309DCE296AF7 (implantation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE implantation (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_16DC605C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, activity_id INT DEFAULT NULL, student_id INT NOT NULL, note VARCHAR(45) NOT NULL, date DATE NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_CFBDFA1481C06096 (activity_id), INDEX IDX_CFBDFA14CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ponderation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, implantation_id INT NOT NULL, name VARCHAR(45) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, INDEX IDX_C5B81ECECE296AF7 (implantation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, birthday DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_classroom (student_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_2E13F11DCB944F1A (student_id), INDEX IDX_2E13F11D6278D5A8 (classroom_id), PRIMARY KEY(student_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(100) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_contact_relation (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, student_id INT NOT NULL, relation VARCHAR(255) NOT NULL, send_school_report TINYINT(1) NOT NULL, INDEX IDX_2AFDFAABE7A1254A (contact_id), INDEX IDX_2AFDFAABCB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_classroom (user_id INT NOT NULL, classroom_id INT NOT NULL, INDEX IDX_499DBD79A76ED395 (user_id), INDEX IDX_499DBD796278D5A8 (classroom_id), PRIMARY KEY(user_id, classroom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_configuration (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, show_logo TINYINT(1) DEFAULT NULL, is_global_config TINYINT(1) NOT NULL, show_footer TINYINT(1) DEFAULT NULL, show_help TINYINT(1) DEFAULT NULL, show_title TINYINT(1) DEFAULT NULL, show_search TINYINT(1) DEFAULT NULL, use_schools TINYINT(1) DEFAULT NULL, use_contacts TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_4B6C0887A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A44EA4809 FOREIGN KEY (note_type_id) REFERENCES note_type (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309DCE296AF7 FOREIGN KEY (implantation_id) REFERENCES implantation (id)');
        $this->addSql('ALTER TABLE implantation ADD CONSTRAINT FK_16DC605C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE period ADD CONSTRAINT FK_C5B81ECECE296AF7 FOREIGN KEY (implantation_id) REFERENCES implantation (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student_classroom ADD CONSTRAINT FK_2E13F11DCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_classroom ADD CONSTRAINT FK_2E13F11D6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_contact_relation ADD CONSTRAINT FK_2AFDFAABE7A1254A FOREIGN KEY (contact_id) REFERENCES student_contact (id)');
        $this->addSql('ALTER TABLE student_contact_relation ADD CONSTRAINT FK_2AFDFAABCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE user_classroom ADD CONSTRAINT FK_499DBD79A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_classroom ADD CONSTRAINT FK_499DBD796278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_configuration ADD CONSTRAINT FK_4B6C0887A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1481C06096');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A6278D5A8');
        $this->addSql('ALTER TABLE student_classroom DROP FOREIGN KEY FK_2E13F11D6278D5A8');
        $this->addSql('ALTER TABLE user_classroom DROP FOREIGN KEY FK_499DBD796278D5A8');
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309DCE296AF7');
        $this->addSql('ALTER TABLE period DROP FOREIGN KEY FK_C5B81ECECE296AF7');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A44EA4809');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AEC8B7ADE');
        $this->addSql('ALTER TABLE implantation DROP FOREIGN KEY FK_16DC605C32A47EE');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14CB944F1A');
        $this->addSql('ALTER TABLE student_classroom DROP FOREIGN KEY FK_2E13F11DCB944F1A');
        $this->addSql('ALTER TABLE student_contact_relation DROP FOREIGN KEY FK_2AFDFAABCB944F1A');
        $this->addSql('ALTER TABLE student_contact_relation DROP FOREIGN KEY FK_2AFDFAABE7A1254A');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AA76ED395');
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309D7E3C61F9');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user_classroom DROP FOREIGN KEY FK_499DBD79A76ED395');
        $this->addSql('ALTER TABLE user_configuration DROP FOREIGN KEY FK_4B6C0887A76ED395');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE implantation');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE note_type');
        $this->addSql('DROP TABLE period');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_classroom');
        $this->addSql('DROP TABLE student_contact');
        $this->addSql('DROP TABLE student_contact_relation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_classroom');
        $this->addSql('DROP TABLE user_configuration');
    }
}
