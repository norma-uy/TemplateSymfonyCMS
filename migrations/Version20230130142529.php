<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130142529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_collection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, original_file_name VARCHAR(255) NOT NULL, original_name VARCHAR(255) NOT NULL, size INT NOT NULL, mime_type VARCHAR(100) NOT NULL, dimensions JSON DEFAULT NULL, image_file_name100w VARCHAR(255) DEFAULT NULL, image_file_name150w VARCHAR(255) DEFAULT NULL, image_file_name300w VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10CF675F31B ON media (author_id)');
        $this->addSql('COMMENT ON COLUMN media.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media_collection (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, link_to VARCHAR(500) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, set_as_home_slider BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F668ABA6989D9B62 ON media_collection (slug)');
        $this->addSql('CREATE INDEX IDX_F668ABA6F675F31B ON media_collection (author_id)');
        $this->addSql('COMMENT ON COLUMN media_collection.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE media_collection_media (media_collection_id INT NOT NULL, media_id INT NOT NULL, PRIMARY KEY(media_collection_id, media_id))');
        $this->addSql('CREATE INDEX IDX_677DC16AB52E685C ON media_collection_media (media_collection_id)');
        $this->addSql('CREATE INDEX IDX_677DC16AEA9FDD75 ON media_collection_media (media_id)');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, author_id INT NOT NULL, thumbnail_photo_id INT DEFAULT NULL, media_slider_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, summary TEXT DEFAULT NULL, content TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, featured BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 ON post (slug)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D5765217F ON post (thumbnail_photo_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D9AC25B6 ON post (media_slider_id)');
        $this->addSql('COMMENT ON COLUMN post.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN post.published_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE post_category (id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, enable_menu BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE post_category_post (post_category_id INT NOT NULL, post_id INT NOT NULL, PRIMARY KEY(post_category_id, post_id))');
        $this->addSql('CREATE INDEX IDX_CE60A8D9FE0617CD ON post_category_post (post_category_id)');
        $this->addSql('CREATE INDEX IDX_CE60A8D94B89032C ON post_category_post (post_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_collection ADD CONSTRAINT FK_F668ABA6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_collection_media ADD CONSTRAINT FK_677DC16AB52E685C FOREIGN KEY (media_collection_id) REFERENCES media_collection (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_collection_media ADD CONSTRAINT FK_677DC16AEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D5765217F FOREIGN KEY (thumbnail_photo_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9AC25B6 FOREIGN KEY (media_slider_id) REFERENCES media_collection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_category_post ADD CONSTRAINT FK_CE60A8D9FE0617CD FOREIGN KEY (post_category_id) REFERENCES post_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_category_post ADD CONSTRAINT FK_CE60A8D94B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_collection_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CF675F31B');
        $this->addSql('ALTER TABLE media_collection DROP CONSTRAINT FK_F668ABA6F675F31B');
        $this->addSql('ALTER TABLE media_collection_media DROP CONSTRAINT FK_677DC16AB52E685C');
        $this->addSql('ALTER TABLE media_collection_media DROP CONSTRAINT FK_677DC16AEA9FDD75');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D5765217F');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D9AC25B6');
        $this->addSql('ALTER TABLE post_category_post DROP CONSTRAINT FK_CE60A8D9FE0617CD');
        $this->addSql('ALTER TABLE post_category_post DROP CONSTRAINT FK_CE60A8D94B89032C');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_collection');
        $this->addSql('DROP TABLE media_collection_media');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE post_category_post');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
