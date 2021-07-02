<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210630120343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, delivery_adress_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone INT NOT NULL, created_at DATETIME NOT NULL, adress VARCHAR(255) DEFAULT NULL, code_postal INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C7440455C0E3B53E (delivery_adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_adress (id INT AUTO_INCREMENT NOT NULL, adress VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, phone INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, id_product_id INT NOT NULL, id_client_id INT NOT NULL, id_delivery_adress_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, payement_method VARCHAR(255) DEFAULT NULL, INDEX IDX_F5299398E00EE68D (id_product_id), UNIQUE INDEX UNIQ_F529939899DED506 (id_client_id), UNIQUE INDEX UNIQ_F529939843C10791 (id_delivery_adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, order_name VARCHAR(255) NOT NULL, price_transaction INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455C0E3B53E FOREIGN KEY (delivery_adress_id) REFERENCES delivery_adress (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939899DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939843C10791 FOREIGN KEY (id_delivery_adress_id) REFERENCES delivery_adress (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939899DED506');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455C0E3B53E');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939843C10791');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E00EE68D');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE delivery_adress');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE product');
    }
}
