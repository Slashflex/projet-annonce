<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430193253 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE annonce_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utilisateur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE annonce (id INT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(60) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, introduction TEXT DEFAULT NULL, contenu TEXT DEFAULT NULL, image_couverture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F65593E560BB6FE6 ON annonce (auteur_id)');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, annonce_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, legende VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F8805AB2F ON image (annonce_id)');
        $this->addSql('CREATE TABLE utilisateur (id INT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, mot_de_passe VARCHAR(255) NOT NULL, description TEXT NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_utilisateur (role_id INT NOT NULL, utilisateur_id INT NOT NULL, PRIMARY KEY(role_id, utilisateur_id))');
        $this->addSql('CREATE INDEX IDX_2F4B3B3AD60322AC ON role_utilisateur (role_id)');
        $this->addSql('CREATE INDEX IDX_2F4B3B3AFB88E14F ON role_utilisateur (utilisateur_id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_utilisateur ADD CONSTRAINT FK_2F4B3B3AD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_utilisateur ADD CONSTRAINT FK_2F4B3B3AFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F8805AB2F');
        $this->addSql('ALTER TABLE annonce DROP CONSTRAINT FK_F65593E560BB6FE6');
        $this->addSql('ALTER TABLE role_utilisateur DROP CONSTRAINT FK_2F4B3B3AFB88E14F');
        $this->addSql('ALTER TABLE role_utilisateur DROP CONSTRAINT FK_2F4B3B3AD60322AC');
        $this->addSql('DROP SEQUENCE annonce_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utilisateur_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_utilisateur');
    }
}
