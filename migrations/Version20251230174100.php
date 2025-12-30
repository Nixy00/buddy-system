<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251230174100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, initiateur_id INT NOT NULL, interlocuteur_id INT NOT NULL, date_crea DATETIME NOT NULL, statut VARCHAR(20) NOT NULL, INDEX IDX_8A8E26E956D142FC (initiateur_id), INDEX IDX_8A8E26E95DC4D72E (interlocuteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, lieu VARCHAR(150) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, date_limite_inscr DATETIME NOT NULL, capacite INT NOT NULL, statut VARCHAR(20) NOT NULL, date_crea DATETIME NOT NULL, date_modif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, utilisateur_id INT NOT NULL, contenu LONGTEXT NOT NULL, date_envoi DATETIME NOT NULL, date_lecture DATETIME DEFAULT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), INDEX IDX_B6BD307FFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, evenement_id INT NOT NULL, INDEX IDX_E6E132B4FB88E14F (utilisateur_id), INDEX IDX_E6E132B4FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, evenement_id INT NOT NULL, date_inscription DATETIME NOT NULL, date_desinscription DATETIME DEFAULT NULL, statut VARCHAR(20) NOT NULL, remarque VARCHAR(255) DEFAULT NULL, INDEX IDX_AB55E24FFB88E14F (utilisateur_id), INDEX IDX_AB55E24FFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE possession (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_F9EE3F42FB88E14F (utilisateur_id), INDEX IDX_F9EE3F42D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, genre VARCHAR(30) NOT NULL, type_compte VARCHAR(30) NOT NULL, date_naissance DATE NOT NULL, telephone VARCHAR(15) DEFAULT NULL, biographie VARCHAR(500) DEFAULT NULL, disponibilites VARCHAR(255) DEFAULT NULL, langues VARCHAR(255) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, pays VARCHAR(100) DEFAULT NULL, avatar LONGBLOB DEFAULT NULL, date_crea DATETIME NOT NULL, date_modif DATETIME NOT NULL, UNIQUE INDEX UNIQ_E6D6B297FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, envoyeur_id INT NOT NULL, receveur_id INT NOT NULL, statut VARCHAR(20) NOT NULL, date_demande DATETIME NOT NULL, date_reponse DATETIME DEFAULT NULL, INDEX IDX_628947494795A786 (envoyeur_id), INDEX IDX_62894749B967E626 (receveur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(20) NOT NULL, libelle VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signalement (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, utilisateur_id INT NOT NULL, motif VARCHAR(255) NOT NULL, date_signalement DATETIME NOT NULL, statut VARCHAR(20) NOT NULL, commentaire_moderation LONGTEXT DEFAULT NULL, INDEX IDX_F4B55114537A1329 (message_id), INDEX IDX_F4B55114FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(40) NOT NULL, mdp_hache VARCHAR(255) NOT NULL, email VARCHAR(190) NOT NULL, date_crea DATETIME NOT NULL, date_derniere_connexion DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E956D142FC FOREIGN KEY (initiateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E95DC4D72E FOREIGN KEY (interlocuteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B4FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE possession ADD CONSTRAINT FK_F9EE3F42FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE possession ADD CONSTRAINT FK_F9EE3F42D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_628947494795A786 FOREIGN KEY (envoyeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749B967E626 FOREIGN KEY (receveur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E956D142FC');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E95DC4D72E');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FFB88E14F');
        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B4FB88E14F');
        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B4FD02F13');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFB88E14F');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFD02F13');
        $this->addSql('ALTER TABLE possession DROP FOREIGN KEY FK_F9EE3F42FB88E14F');
        $this->addSql('ALTER TABLE possession DROP FOREIGN KEY FK_F9EE3F42D60322AC');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297FB88E14F');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_628947494795A786');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749B967E626');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114537A1329');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114FB88E14F');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE possession');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE signalement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
