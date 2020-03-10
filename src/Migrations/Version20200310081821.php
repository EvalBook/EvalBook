<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200310081821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, id_note_type_id INT NOT NULL, id_knowledge_id INT DEFAULT NULL, id_matiere_id INT DEFAULT NULL, id_user_id INT NOT NULL, id_periode_id INT NOT NULL, active_in_period TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_B8755515672B3D7B (id_note_type_id), INDEX IDX_B8755515C41D1C70 (id_knowledge_id), INDEX IDX_B875551551E6528F (id_matiere_id), INDEX IDX_B875551579F37AE5 (id_user_id), INDEX IDX_B8755515560E4118 (id_periode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, id_type_classe_id INT NOT NULL, name VARCHAR(45) NOT NULL, INDEX IDX_8F87BF966FE591F (id_type_classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, zip_code VARCHAR(10) NOT NULL, country VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve_classe (eleve_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_564E8557A6CC7B2 (eleve_id), INDEX IDX_564E85578F5EA509 (classe_id), PRIMARY KEY(eleve_id, classe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve_contact (eleve_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_A0D8DD24A6CC7B2 (eleve_id), INDEX IDX_A0D8DD24E7A1254A (contact_id), PRIMARY KEY(eleve_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE knowledge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, id_eleve_id INT NOT NULL, note VARCHAR(45) NOT NULL, date DATE NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_CFBDFA145AB72B27 (id_eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_activite (note_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_A82E898B26ED0855 (note_id), INDEX IDX_A82E898B9B0F88B1 (activite_id), PRIMARY KEY(note_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ponderation VARCHAR(255) NOT NULL, coefficient INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE periode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, active TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, print_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_classe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64938C751C4 (roles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_classe (user_id INT NOT NULL, classe_id INT NOT NULL, INDEX IDX_EAD5A4ABA76ED395 (user_id), INDEX IDX_EAD5A4AB8F5EA509 (classe_id), PRIMARY KEY(user_id, classe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515672B3D7B FOREIGN KEY (id_note_type_id) REFERENCES note_type (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515C41D1C70 FOREIGN KEY (id_knowledge_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551551E6528F FOREIGN KEY (id_matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B875551579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515560E4118 FOREIGN KEY (id_periode_id) REFERENCES periode (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF966FE591F FOREIGN KEY (id_type_classe_id) REFERENCES type_classe (id)');
        $this->addSql('ALTER TABLE eleve_classe ADD CONSTRAINT FK_564E8557A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleve_classe ADD CONSTRAINT FK_564E85578F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleve_contact ADD CONSTRAINT FK_A0D8DD24A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eleve_contact ADD CONSTRAINT FK_A0D8DD24E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA145AB72B27 FOREIGN KEY (id_eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE note_activite ADD CONSTRAINT FK_A82E898B26ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_activite ADD CONSTRAINT FK_A82E898B9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64938C751C4 FOREIGN KEY (roles_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user_classe ADD CONSTRAINT FK_EAD5A4ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_classe ADD CONSTRAINT FK_EAD5A4AB8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note_activite DROP FOREIGN KEY FK_A82E898B9B0F88B1');
        $this->addSql('ALTER TABLE eleve_classe DROP FOREIGN KEY FK_564E85578F5EA509');
        $this->addSql('ALTER TABLE user_classe DROP FOREIGN KEY FK_EAD5A4AB8F5EA509');
        $this->addSql('ALTER TABLE eleve_contact DROP FOREIGN KEY FK_A0D8DD24E7A1254A');
        $this->addSql('ALTER TABLE eleve_classe DROP FOREIGN KEY FK_564E8557A6CC7B2');
        $this->addSql('ALTER TABLE eleve_contact DROP FOREIGN KEY FK_A0D8DD24A6CC7B2');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA145AB72B27');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515C41D1C70');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551551E6528F');
        $this->addSql('ALTER TABLE note_activite DROP FOREIGN KEY FK_A82E898B26ED0855');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515672B3D7B');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515560E4118');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64938C751C4');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF966FE591F');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B875551579F37AE5');
        $this->addSql('ALTER TABLE user_classe DROP FOREIGN KEY FK_EAD5A4ABA76ED395');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE eleve_classe');
        $this->addSql('DROP TABLE eleve_contact');
        $this->addSql('DROP TABLE knowledge');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE note_activite');
        $this->addSql('DROP TABLE note_type');
        $this->addSql('DROP TABLE periode');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE type_classe');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_classe');
    }
}
