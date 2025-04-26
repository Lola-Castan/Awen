<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426193758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE event_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_category_event (event_category_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_CD9F39DB9CF4E62 (event_category_id), INDEX IDX_CD9F39D71F7E88B (event_id), PRIMARY KEY(event_category_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category_event ADD CONSTRAINT FK_CD9F39DB9CF4E62 FOREIGN KEY (event_category_id) REFERENCES event_category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category_event ADD CONSTRAINT FK_CD9F39D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category_event DROP FOREIGN KEY FK_CD9F39DB9CF4E62
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category_event DROP FOREIGN KEY FK_CD9F39D71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_category_event
        SQL);
    }
}
