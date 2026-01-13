<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260113112249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signalement ADD reported_user_id INT DEFAULT NULL, ADD reported_event_id INT DEFAULT NULL, CHANGE message_id message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B55114E7566E FOREIGN KEY (reported_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE signalement ADD CONSTRAINT FK_F4B551145B3F4A9A FOREIGN KEY (reported_event_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_F4B55114E7566E ON signalement (reported_user_id)');
        $this->addSql('CREATE INDEX IDX_F4B551145B3F4A9A ON signalement (reported_event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B55114E7566E');
        $this->addSql('ALTER TABLE signalement DROP FOREIGN KEY FK_F4B551145B3F4A9A');
        $this->addSql('DROP INDEX IDX_F4B55114E7566E ON signalement');
        $this->addSql('DROP INDEX IDX_F4B551145B3F4A9A ON signalement');
        $this->addSql('ALTER TABLE signalement DROP reported_user_id, DROP reported_event_id, CHANGE message_id message_id INT NOT NULL');
    }
}
