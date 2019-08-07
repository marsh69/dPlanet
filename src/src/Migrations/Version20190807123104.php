<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190807123104 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image (id VARCHAR(255) NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, mime_type VARCHAR(255) DEFAULT \'\' NOT NULL, filename VARCHAR(255) DEFAULT \'\' NOT NULL, file_path VARCHAR(255) DEFAULT \'\' NOT NULL, public_path VARCHAR(255) DEFAULT \'\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id VARCHAR(255) NOT NULL, posted_by_id VARCHAR(255) DEFAULT NULL, posted_to_id VARCHAR(255) DEFAULT NULL, body LONGTEXT NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9474526C5A6D2235 (posted_by_id), INDEX IDX_9474526CC0BF4854 (posted_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id VARCHAR(255) NOT NULL, sender_id VARCHAR(255) DEFAULT NULL, receiver_id VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, is_viewed TINYINT(1) DEFAULT \'0\' NOT NULL, is_opened TINYINT(1) DEFAULT \'0\' NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BF5476CAF624B39D (sender_id), INDEX IDX_BF5476CACD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id VARCHAR(255) NOT NULL, profile_image_id VARCHAR(255) DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', first_name VARCHAR(255) DEFAULT \'\' NOT NULL, last_name VARCHAR(255) DEFAULT \'\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_957A6479C4CF44DC (profile_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friend_request (id VARCHAR(255) NOT NULL, sender_id VARCHAR(255) DEFAULT NULL, receiver_id VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F284D94F624B39D (sender_id), INDEX IDX_F284D94CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id VARCHAR(255) NOT NULL, sender_id VARCHAR(255) DEFAULT NULL, receiver_id VARCHAR(255) DEFAULT NULL, body LONGTEXT NOT NULL, is_opened TINYINT(1) DEFAULT \'0\' NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_likes (id VARCHAR(255) NOT NULL, developer_id VARCHAR(255) DEFAULT NULL, post_id VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9F19C3B064DD9267 (developer_id), INDEX IDX_9F19C3B04B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id VARCHAR(255) NOT NULL, image_id VARCHAR(255) DEFAULT NULL, posted_by_id VARCHAR(255) DEFAULT NULL, body LONGTEXT NOT NULL, is_deleted TINYINT(1) DEFAULT \'0\' NOT NULL, is_closed_by_user TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5A8A6C8D3DA5256D (image_id), INDEX IDX_5A8A6C8D5A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC0BF4854 FOREIGN KEY (posted_to_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479C4CF44DC FOREIGN KEY (profile_image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE friend_request ADD CONSTRAINT FK_F284D94F624B39D FOREIGN KEY (sender_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE friend_request ADD CONSTRAINT FK_F284D94CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE app_likes ADD CONSTRAINT FK_9F19C3B064DD9267 FOREIGN KEY (developer_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE app_likes ADD CONSTRAINT FK_9F19C3B04B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES fos_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479C4CF44DC');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D3DA5256D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C5A6D2235');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF624B39D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CACD53EDB6');
        $this->addSql('ALTER TABLE friend_request DROP FOREIGN KEY FK_F284D94F624B39D');
        $this->addSql('ALTER TABLE friend_request DROP FOREIGN KEY FK_F284D94CD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE app_likes DROP FOREIGN KEY FK_9F19C3B064DD9267');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D5A6D2235');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC0BF4854');
        $this->addSql('ALTER TABLE app_likes DROP FOREIGN KEY FK_9F19C3B04B89032C');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE friend_request');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE app_likes');
        $this->addSql('DROP TABLE post');
    }
}
