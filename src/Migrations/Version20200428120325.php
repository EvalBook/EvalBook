<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200428120325 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555155FB14BA7');
        $this->addSql('ALTER TABLE implantation DROP FOREIGN KEY FK_16DC60577EF1B1E');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515E7DC6902');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515F46CD258');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF963B98E419');
        $this->addSql('DROP TABLE activite_level');
        $this->addSql('DROP TABLE ecole');
        $this->addSql('DROP TABLE knowledge');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE type_classe');
        $this->addSql('DROP INDEX IDX_B8755515F46CD258 ON activite');
        $this->addSql('DROP INDEX IDX_B87555155FB14BA7 ON activite');
        $this->addSql('DROP INDEX IDX_B8755515E7DC6902 ON activite');
        $this->addSql('ALTER TABLE activite DROP knowledge_id, DROP matiere_id, DROP level_id');
        $this->addSql('DROP INDEX IDX_8F87BF963B98E419 ON classe');
        $this->addSql('ALTER TABLE classe DROP type_classe_id');
        $this->addSql('DROP INDEX IDX_16DC60577EF1B1E ON implantation');
        $this->addSql('ALTER TABLE implantation DROP ecole_id, DROP default_implantation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activite_level (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ecole (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE knowledge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type_classe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activite ADD knowledge_id INT DEFAULT NULL, ADD matiere_id INT DEFAULT NULL, ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555155FB14BA7 FOREIGN KEY (level_id) REFERENCES activite_level (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515E7DC6902 FOREIGN KEY (knowledge_id) REFERENCES knowledge (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_B8755515F46CD258 ON activite (matiere_id)');
        $this->addSql('CREATE INDEX IDX_B87555155FB14BA7 ON activite (level_id)');
        $this->addSql('CREATE INDEX IDX_B8755515E7DC6902 ON activite (knowledge_id)');
        $this->addSql('ALTER TABLE classe ADD type_classe_id INT NOT NULL');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF963B98E419 FOREIGN KEY (type_classe_id) REFERENCES type_classe (id)');
        $this->addSql('CREATE INDEX IDX_8F87BF963B98E419 ON classe (type_classe_id)');
        $this->addSql('ALTER TABLE implantation ADD ecole_id INT NOT NULL, ADD default_implantation TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE implantation ADD CONSTRAINT FK_16DC60577EF1B1E FOREIGN KEY (ecole_id) REFERENCES ecole (id)');
        $this->addSql('CREATE INDEX IDX_16DC60577EF1B1E ON implantation (ecole_id)');
    }
}
