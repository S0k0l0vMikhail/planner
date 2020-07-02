<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Добавляет таблицу для хранения графиков отпусков
 */
final class Version20200626132738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE table_vacation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, deleted TINYINT(1) NOT NULL, approved_at DATETIME DEFAULT NULL COMMENT \'Дата утверждения графика\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7367F1A5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'График отпусков\' ');
        $this->addSql('UPDATE table_vacation SET created_at=NOW(), updated_at=NOW()');
        $this->addSql('ALTER TABLE table_vacation ADD CONSTRAINT FK_7367F1A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE table_vacation');
    }
}
