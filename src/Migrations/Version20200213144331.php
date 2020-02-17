<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200213144331 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shopping_cart_product (shopping_cart_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_FA1F5E6C45F80CD (shopping_cart_id), INDEX IDX_FA1F5E6C4584665A (product_id), PRIMARY KEY(shopping_cart_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart_product ADD CONSTRAINT FK_FA1F5E6C45F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shopping_cart_product ADD CONSTRAINT FK_FA1F5E6C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shopping_cart_product');
    }
}
