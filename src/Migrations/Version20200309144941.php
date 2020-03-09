<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309144941 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, id_note_type_fk_id INT NOT NULL, id_knowledge_fk_id INT DEFAULT NULL, id_matiere_fk_id INT DEFAULT NULL, id_periode_fk_id INT NOT NULL, id_user_fk_id INT NOT NULL, active_in_period TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, name VARCHAR(250) NOT NULL, INDEX IDX_B87555152282749F (id_note_type_fk_id), INDEX IDX_B87555156DF21BA7 (id_knowledge_fk_id), INDEX IDX_B875551597FF66C0 (id_matiere_fk_id), INDEX IDX_B8755515A09DE36A (id_periode_fk_id), INDEX IDX_B8755515E23F625F (id_user_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, id_type_classe_fk_id INT NOT NULL, name VARCHAR(45) NOT NULL, INDEX IDX_8F87BF969A749FFA (id_type_classe_fk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, zip_code VARCHAR(6) NOT NULL, country VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, id_contact_fk_id INT NOT NULL, id_contact_fk_secondary_id INT NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_ECA105F73675D8BA (id_contact_fk_id), INDEX IDX_ECA105F7113CF27B (id_contact_fk_secondary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve_classe (eleve_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_564E8557A6CC7B2 (eleve_id), INDEX IDX_564E85578F5EA509 (classe_id), PRIMARY KEY(eleve_id, classe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE knowledge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, ponderation VARCHAR(45) NOT NULL, coefficient INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, active TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, print_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(250) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_classe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555152282749F FOREIGN KEY (id_note_type_fk_id) REFERENCES note_type (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555156DF21BA7 FOREIGN KEY (id_knowledge_fk_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551597FF66C0 FOREIGN KEY (id_matiere_fk_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515A09DE36A FOREIGN KEY (id_periode_fk_id) REFERENCES periode (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515E23F625F FOREIGN KEY (id_user_fk_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF969A749FFA FOREIGN KEY (id_type_classe_fk_id) REFERENCES type_classe (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F73675D8BA FOREIGN KEY (id_contact_fk_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7113CF27B FOREIGN KEY (id_contact_fk_secondary_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE eleve_classe ADD CONSTRAINT FK_564E8557A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleve_classe ADD CONSTRAINT FK_564E85578F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE eleve_classe DROP FOREIGN KEY FK_564E85578F5EA509');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F73675D8BA');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7113CF27B');
        $this->addSql('ALTER TABLE eleve_classe DROP FOREIGN KEY FK_564E8557A6CC7B2');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555156DF21BA7');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551597FF66C0');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555152282749F');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515A09DE36A');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF969A749FFA');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515E23F625F');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE eleve_classe');
        $this->addSql('DROP TABLE knowledge');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE note_type');
        $this->addSql('DROP TABLE periode');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE type_classe');
        $this->addSql('DROP TABLE user');
    }
}
