<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260114180308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversation_suppressions (conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C29D01329AC0396 (conversation_id), INDEX IDX_C29D0132A76ED395 (user_id), PRIMARY KEY(conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_archives (conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_22C53299AC0396 (conversation_id), INDEX IDX_22C5329A76ED395 (user_id), PRIMARY KEY(conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation_suppressions ADD CONSTRAINT FK_C29D01329AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_suppressions ADD CONSTRAINT FK_C29D0132A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_archives ADD CONSTRAINT FK_22C53299AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_archives ADD CONSTRAINT FK_22C5329A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation_suppressions DROP FOREIGN KEY FK_C29D01329AC0396');
        $this->addSql('ALTER TABLE conversation_suppressions DROP FOREIGN KEY FK_C29D0132A76ED395');
        $this->addSql('ALTER TABLE conversation_archives DROP FOREIGN KEY FK_22C53299AC0396');
        $this->addSql('ALTER TABLE conversation_archives DROP FOREIGN KEY FK_22C5329A76ED395');
        $this->addSql('DROP TABLE conversation_suppressions');
        $this->addSql('DROP TABLE conversation_archives');
    }
}
