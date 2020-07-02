<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200625135507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department ADD master_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A7B493EFD FOREIGN KEY (master_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CD1DE18A7B493EFD ON department (master_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A7B493EFD');
        $this->addSql('DROP INDEX IDX_CD1DE18A7B493EFD ON department');
        $this->addSql('ALTER TABLE department DROP master_user_id');
    }
}
