<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501154600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biblio_book (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, auteur VARCHAR(255) DEFAULT NULL, editeur VARCHAR(255) DEFAULT NULL, dewey INT DEFAULT NULL, prix INT DEFAULT NULL, date_ajout DATETIME NOT NULL, is_dispo TINYINT(1) NOT NULL, date_indispo DATETIME DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE biblio_emprunt (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, livre_id INT NOT NULL, is_emprunt TINYINT(1) NOT NULL, date_emprunt DATETIME NOT NULL, date_retour DATETIME DEFAULT NULL, INDEX IDX_22CB3A91A6CC7B2 (eleve_id), INDEX IDX_22CB3A9137D925CB (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE biblio_section (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rang INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE biblio_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, sexe VARCHAR(255) NOT NULL, section VARCHAR(255) NOT NULL, is_caution TINYINT(1) NOT NULL, emprunts INT NOT NULL, ine VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4A04488FF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biblio_emprunt ADD CONSTRAINT FK_22CB3A91A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES biblio_user (id)');
        $this->addSql('ALTER TABLE biblio_emprunt ADD CONSTRAINT FK_22CB3A9137D925CB FOREIGN KEY (livre_id) REFERENCES biblio_book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biblio_emprunt DROP FOREIGN KEY FK_22CB3A9137D925CB');
        $this->addSql('ALTER TABLE biblio_emprunt DROP FOREIGN KEY FK_22CB3A91A6CC7B2');
        $this->addSql('DROP TABLE biblio_book');
        $this->addSql('DROP TABLE biblio_emprunt');
        $this->addSql('DROP TABLE biblio_section');
        $this->addSql('DROP TABLE biblio_user');
    }
}
