<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209093818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE videos ADD video_user_poster_id INT NOT NULL');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA64327D84AB61 FOREIGN KEY (video_user_poster_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_29AA64327D84AB61 ON videos (video_user_poster_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA64327D84AB61');
        $this->addSql('DROP INDEX IDX_29AA64327D84AB61 ON videos');
        $this->addSql('ALTER TABLE videos DROP video_user_poster_id');
    }
}
