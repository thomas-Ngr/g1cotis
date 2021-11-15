<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115133324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dispatch_recipient (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, address VARCHAR(48) NOT NULL, percent SMALLINT DEFAULT NULL, INDEX IDX_718F5D65712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, address VARCHAR(48) DEFAULT NULL, last_done DATETIME DEFAULT NULL, time_interval VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', type SMALLINT NOT NULL, INDEX IDX_7C68921FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dispatch_recipient ADD CONSTRAINT FK_718F5D65712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921FA76ED395');
        $this->addSql('ALTER TABLE dispatch_recipient DROP FOREIGN KEY FK_718F5D65712520F3');
        $this->addSql('DROP TABLE dispatch_recipient');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
    }
}
