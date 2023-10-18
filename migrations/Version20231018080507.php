<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018080507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE videos_tags (video_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_CD9528D229C1004E (video_id), INDEX IDX_CD9528D2BAD26311 (tag_id), PRIMARY KEY(video_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE videos_tags ADD CONSTRAINT FK_CD9528D229C1004E FOREIGN KEY (video_id) REFERENCES videos (id)');
        $this->addSql('ALTER TABLE videos_tags ADD CONSTRAINT FK_CD9528D2BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE videos_tags DROP FOREIGN KEY FK_CD9528D229C1004E');
        $this->addSql('ALTER TABLE videos_tags DROP FOREIGN KEY FK_CD9528D2BAD26311');
        $this->addSql('DROP TABLE videos_tags');
    }
}
