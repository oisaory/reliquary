<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722233533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create RelicImage and UserImage tables to replace the Image table';
    }

    public function up(Schema $schema): void
    {
        // Create new tables for RelicImage and UserImage
        $this->addSql('CREATE SEQUENCE relic_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE relic_image (id INT NOT NULL, relic_id INT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CB6D43D295EB414 ON relic_image (relic_id)');
        $this->addSql('CREATE TABLE user_image (id INT NOT NULL, user_id INT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_27FFFF07A76ED395 ON user_image (user_id)');
        $this->addSql('ALTER TABLE relic_image ADD CONSTRAINT FK_CB6D43D295EB414 FOREIGN KEY (relic_id) REFERENCES relic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_image ADD CONSTRAINT FK_27FFFF07A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Migrate data from the old image table to the new tables
        // First, migrate relic images
        $this->addSql('INSERT INTO relic_image (id, relic_id, filename, original_filename, mime_type, size)
                      SELECT i.id, CAST(i.owner_id AS INT), i.filename, i.original_filename, i.mime_type, i.size
                      FROM image i
                      WHERE i.owner_type = \'relic\'');

        // Then, migrate user images
        $this->addSql('INSERT INTO user_image (id, user_id, filename, original_filename, mime_type, size)
                      SELECT i.id, CAST(i.owner_id AS INT), i.filename, i.original_filename, i.mime_type, i.size
                      FROM image i
                      WHERE i.owner_type = \'user\'');

        // Update the sequences to start after the highest migrated ID
        $this->addSql('SELECT setval(\'relic_image_id_seq\', COALESCE((SELECT MAX(id) FROM relic_image), 0) + 1, false)');
        $this->addSql('SELECT setval(\'user_image_id_seq\', COALESCE((SELECT MAX(id) FROM user_image), 0) + 1, false)');

        // Drop the old image table
        $this->addSql('DROP TABLE IF EXISTS image');
    }

    public function down(Schema $schema): void
    {
        // Recreate the original image table
        $this->addSql('CREATE TABLE image (
            id INT NOT NULL, 
            owner_id INT DEFAULT NULL, 
            filename VARCHAR(255) NOT NULL, 
            original_filename VARCHAR(255) DEFAULT NULL, 
            mime_type VARCHAR(255) NOT NULL, 
            size INT NOT NULL, 
            owner_type VARCHAR(50) NOT NULL, 
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_C53D045F7E3C61F9 ON image (owner_id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES relic (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Migrate data back from relic_image to image
        $this->addSql('INSERT INTO image (id, owner_id, filename, original_filename, mime_type, size, owner_type)
                      SELECT ri.id, ri.relic_id, ri.filename, ri.original_filename, ri.mime_type, ri.size, \'relic\'
                      FROM relic_image ri');

        // Migrate data back from user_image to image
        $this->addSql('INSERT INTO image (id, owner_id, filename, original_filename, mime_type, size, owner_type)
                      SELECT ui.id, ui.user_id, ui.filename, ui.original_filename, ui.mime_type, ui.size, \'user\'
                      FROM user_image ui');

        // Drop the new tables
        $this->addSql('DROP SEQUENCE relic_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_image_id_seq CASCADE');
        $this->addSql('ALTER TABLE relic_image DROP CONSTRAINT FK_CB6D43D295EB414');
        $this->addSql('ALTER TABLE user_image DROP CONSTRAINT FK_27FFFF07A76ED395');
        $this->addSql('DROP TABLE relic_image');
        $this->addSql('DROP TABLE user_image');
    }
}
