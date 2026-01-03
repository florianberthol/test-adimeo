<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103093635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD type VARCHAR(50) NOT NULL, CHANGE image url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image RENAME INDEX uniq_image_date TO UNIQ_C53D045FAA9E377A');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP type, CHANGE url image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image RENAME INDEX uniq_c53d045faa9e377a TO UNIQ_IMAGE_DATE');
    }
}
