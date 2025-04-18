<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418081102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category_creator (category_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7BDEAA1D12469DE2 (category_id), INDEX IDX_7BDEAA1DA76ED395 (user_id), PRIMARY KEY(category_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category_product (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_149244D34584665A (product_id), INDEX IDX_149244D312469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_creator ADD CONSTRAINT FK_7BDEAA1D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_creator ADD CONSTRAINT FK_7BDEAA1DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_product ADD CONSTRAINT FK_149244D34584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_product ADD CONSTRAINT FK_149244D312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category_creator DROP FOREIGN KEY FK_7BDEAA1D12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_creator DROP FOREIGN KEY FK_7BDEAA1DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_product DROP FOREIGN KEY FK_149244D34584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category_product DROP FOREIGN KEY FK_149244D312469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category_creator
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category_product
        SQL);
    }
}
