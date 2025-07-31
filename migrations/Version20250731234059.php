<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250731234059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE saint_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE saint_translation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE saint_image (id INT NOT NULL, uploader_id INT DEFAULT NULL, saint_id INT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E123D27E16678C77 ON saint_image (uploader_id)');
        $this->addSql('CREATE INDEX IDX_E123D27E1E98B21C ON saint_image (saint_id)');
        $this->addSql('CREATE TABLE saint_translation (id INT NOT NULL, saint_id INT NOT NULL, locale VARCHAR(5) NOT NULL, name VARCHAR(255) DEFAULT NULL, saint_phrase TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F1AED7951E98B21C ON saint_translation (saint_id)');
        $this->addSql('ALTER TABLE saint_image ADD CONSTRAINT FK_E123D27E16678C77 FOREIGN KEY (uploader_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE saint_image ADD CONSTRAINT FK_E123D27E1E98B21C FOREIGN KEY (saint_id) REFERENCES saint (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE saint_translation ADD CONSTRAINT FK_F1AED7951E98B21C FOREIGN KEY (saint_id) REFERENCES saint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relic ALTER provenance DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE saint_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE saint_translation_id_seq CASCADE');
        $this->addSql('ALTER TABLE saint_image DROP CONSTRAINT FK_E123D27E16678C77');
        $this->addSql('ALTER TABLE saint_image DROP CONSTRAINT FK_E123D27E1E98B21C');
        $this->addSql('ALTER TABLE saint_translation DROP CONSTRAINT FK_F1AED7951E98B21C');
        $this->addSql('DROP TABLE saint_image');
        $this->addSql('DROP TABLE saint_translation');
        $this->addSql('ALTER TABLE relic ALTER provenance SET DEFAULT \'\'');
    }
}
