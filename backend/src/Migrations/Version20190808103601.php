<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190808103601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trend (id VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT \'\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_trend (post_id VARCHAR(255) NOT NULL, trend_id VARCHAR(255) NOT NULL, INDEX IDX_63E0BF494B89032C (post_id), INDEX IDX_63E0BF4965B0AAB2 (trend_id), PRIMARY KEY(post_id, trend_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_trend ADD CONSTRAINT FK_63E0BF494B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_trend ADD CONSTRAINT FK_63E0BF4965B0AAB2 FOREIGN KEY (trend_id) REFERENCES trend (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post_trend DROP FOREIGN KEY FK_63E0BF4965B0AAB2');
        $this->addSql('DROP TABLE trend');
        $this->addSql('DROP TABLE post_trend');
    }
}
