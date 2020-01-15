<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200115204430 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, populated_place VARCHAR(255) NOT NULL, office VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, order_on DATE NOT NULL, new_or_archived TINYINT(1) NOT NULL, confirmed TINYINT(1) NOT NULL, additional_info VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product DROP added_on, CHANGE is_detelet is_detelet TINYINT(1) NOT NULL, CHANGE is_promotion is_promotion TINYINT(1) NOT NULL, CHANGE discount_price discount_price DOUBLE PRECISION NOT NULL, CHANGE is_shoe is_shoe TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `order`');
        $this->addSql('ALTER TABLE product ADD added_on DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE is_detelet is_detelet TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_promotion is_promotion TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE discount_price discount_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE is_shoe is_shoe TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
