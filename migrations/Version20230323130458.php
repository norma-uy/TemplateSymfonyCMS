<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323130458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', original_file_name VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, size INT NOT NULL, mime_type VARCHAR(100) NOT NULL, dimensions JSON DEFAULT NULL, image_file_name100w VARCHAR(255) DEFAULT NULL, image_file_name150w VARCHAR(255) DEFAULT NULL, image_file_name300w VARCHAR(255) DEFAULT NULL, INDEX IDX_6A2CA10CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_collection (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, link_to VARCHAR(500) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', set_as_home_slider TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F668ABA6989D9B62 (slug), INDEX IDX_F668ABA6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_collection_media (media_collection_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_677DC16AB52E685C (media_collection_id), INDEX IDX_677DC16AEA9FDD75 (media_id), PRIMARY KEY(media_collection_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, thumbnail_photo_id INT DEFAULT NULL, media_slider_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, summary LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', featured TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 (slug), INDEX IDX_5A8A6C8DF675F31B (author_id), INDEX IDX_5A8A6C8D5765217F (thumbnail_photo_id), INDEX IDX_5A8A6C8D9AC25B6 (media_slider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, enable_menu TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category_post (post_category_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_CE60A8D9FE0617CD (post_category_id), INDEX IDX_CE60A8D94B89032C (post_id), PRIMARY KEY(post_category_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE media_collection ADD CONSTRAINT FK_F668ABA6F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE media_collection_media ADD CONSTRAINT FK_677DC16AB52E685C FOREIGN KEY (media_collection_id) REFERENCES media_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_collection_media ADD CONSTRAINT FK_677DC16AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D5765217F FOREIGN KEY (thumbnail_photo_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9AC25B6 FOREIGN KEY (media_slider_id) REFERENCES media_collection (id)');
        $this->addSql('ALTER TABLE post_category_post ADD CONSTRAINT FK_CE60A8D9FE0617CD FOREIGN KEY (post_category_id) REFERENCES post_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_category_post ADD CONSTRAINT FK_CE60A8D94B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CF675F31B');
        $this->addSql('ALTER TABLE media_collection DROP FOREIGN KEY FK_F668ABA6F675F31B');
        $this->addSql('ALTER TABLE media_collection_media DROP FOREIGN KEY FK_677DC16AB52E685C');
        $this->addSql('ALTER TABLE media_collection_media DROP FOREIGN KEY FK_677DC16AEA9FDD75');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D5765217F');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9AC25B6');
        $this->addSql('ALTER TABLE post_category_post DROP FOREIGN KEY FK_CE60A8D9FE0617CD');
        $this->addSql('ALTER TABLE post_category_post DROP FOREIGN KEY FK_CE60A8D94B89032C');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_collection');
        $this->addSql('DROP TABLE media_collection_media');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE post_category_post');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
