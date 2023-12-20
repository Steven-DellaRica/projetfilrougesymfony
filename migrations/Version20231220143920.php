<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220143920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_likes (user_id INT NOT NULL, videos_id INT NOT NULL, INDEX IDX_AB08B525A76ED395 (user_id), INDEX IDX_AB08B525763C10B2 (videos_id), PRIMARY KEY(user_id, videos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_favorites (user_id INT NOT NULL, videos_id INT NOT NULL, INDEX IDX_E489ED11A76ED395 (user_id), INDEX IDX_E489ED11763C10B2 (videos_id), PRIMARY KEY(user_id, videos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B525A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B525763C10B2 FOREIGN KEY (videos_id) REFERENCES videos (id)');
        $this->addSql('ALTER TABLE user_favorites ADD CONSTRAINT FK_E489ED11A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_favorites ADD CONSTRAINT FK_E489ED11763C10B2 FOREIGN KEY (videos_id) REFERENCES videos (id)');
        $this->addSql('ALTER TABLE users_favorites DROP FOREIGN KEY FK_4CF87F0F763C10B2');
        $this->addSql('ALTER TABLE users_favorites DROP FOREIGN KEY FK_4CF87F0FA76ED395');
        $this->addSql('ALTER TABLE users_likes DROP FOREIGN KEY FK_AEBDEA3429C1004E');
        $this->addSql('ALTER TABLE users_likes DROP FOREIGN KEY FK_AEBDEA34A76ED395');
        $this->addSql('DROP TABLE users_favorites');
        $this->addSql('DROP TABLE users_likes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_favorites (user_id INT NOT NULL, videos_id INT NOT NULL, INDEX IDX_4CF87F0F763C10B2 (videos_id), INDEX IDX_4CF87F0FA76ED395 (user_id), PRIMARY KEY(user_id, videos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_likes (user_id INT NOT NULL, videos_id INT NOT NULL, INDEX IDX_AEBDEA3429C1004E (videos_id), INDEX IDX_AEBDEA34A76ED395 (user_id), PRIMARY KEY(user_id, videos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_favorites ADD CONSTRAINT FK_4CF87F0F763C10B2 FOREIGN KEY (videos_id) REFERENCES videos (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_favorites ADD CONSTRAINT FK_4CF87F0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_likes ADD CONSTRAINT FK_AEBDEA3429C1004E FOREIGN KEY (videos_id) REFERENCES videos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users_likes ADD CONSTRAINT FK_AEBDEA34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B525A76ED395');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B525763C10B2');
        $this->addSql('ALTER TABLE user_favorites DROP FOREIGN KEY FK_E489ED11A76ED395');
        $this->addSql('ALTER TABLE user_favorites DROP FOREIGN KEY FK_E489ED11763C10B2');
        $this->addSql('DROP TABLE user_likes');
        $this->addSql('DROP TABLE user_favorites');
    }
}
