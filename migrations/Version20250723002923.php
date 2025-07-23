<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723002923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT NOT NULL, owner_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, owner_type VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F7E3C61F9 ON image (owner_id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES relic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relic_image ADD uploader_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE relic_image ADD CONSTRAINT FK_CB6D43D216678C77 FOREIGN KEY (uploader_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CB6D43D216678C77 ON relic_image (uploader_id)');
        $this->addSql('ALTER TABLE user_image ADD uploader_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_image ADD CONSTRAINT FK_27FFFF0716678C77 FOREIGN KEY (uploader_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_27FFFF0716678C77 ON user_image (uploader_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F7E3C61F9');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE user_image DROP CONSTRAINT FK_27FFFF0716678C77');
        $this->addSql('DROP INDEX IDX_27FFFF0716678C77');
        $this->addSql('ALTER TABLE user_image DROP uploader_id');
        $this->addSql('ALTER TABLE relic_image DROP CONSTRAINT FK_CB6D43D216678C77');
        $this->addSql('DROP INDEX IDX_CB6D43D216678C77');
        $this->addSql('ALTER TABLE relic_image DROP uploader_id');
    }
}
