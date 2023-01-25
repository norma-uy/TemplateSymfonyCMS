<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125195359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category_post (post_category_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_CE60A8D9FE0617CD (post_category_id), INDEX IDX_CE60A8D94B89032C (post_id), PRIMARY KEY(post_category_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_category_post ADD CONSTRAINT FK_CE60A8D9FE0617CD FOREIGN KEY (post_category_id) REFERENCES post_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_category_post ADD CONSTRAINT FK_CE60A8D94B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_category_post DROP FOREIGN KEY FK_CE60A8D9FE0617CD');
        $this->addSql('ALTER TABLE post_category_post DROP FOREIGN KEY FK_CE60A8D94B89032C');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE post_category_post');
    }
}
