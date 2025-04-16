<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416105954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD creator_info_website VARCHAR(100) DEFAULT NULL, ADD creator_info_display_name VARCHAR(50) NOT NULL, ADD creator_info_instagram_profile VARCHAR(255) DEFAULT NULL, ADD creator_info_facebook_profile VARCHAR(255) DEFAULT NULL, ADD creator_info_pinterest_profile VARCHAR(255) DEFAULT NULL, ADD creator_info_description LONGTEXT DEFAULT NULL, ADD creator_info_practical_infos LONGTEXT DEFAULT NULL, ADD creator_info_cover_image VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP creator_info_website, DROP creator_info_display_name, DROP creator_info_instagram_profile, DROP creator_info_facebook_profile, DROP creator_info_pinterest_profile, DROP creator_info_description, DROP creator_info_practical_infos, DROP creator_info_cover_image
        SQL);
    }
}
