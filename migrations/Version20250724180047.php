<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724180047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // PostgreSQL no usa AUTO_INCREMENT, sino SERIAL o GENERATED
        $this->addSql('CREATE TABLE orden (
            id SERIAL PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            pagado BOOLEAN NOT NULL
        )');

        $this->addSql('CREATE TABLE product (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            precio NUMERIC(10, 2) NOT NULL
        )');

        $this->addSql('CREATE TABLE orden_item (
            id SERIAL PRIMARY KEY,
            id_producto INT NOT NULL,
            id_orden INT NOT NULL,
            cantidad INT NOT NULL,
            CONSTRAINT fk_producto FOREIGN KEY (id_producto) REFERENCES product (id) ON DELETE CASCADE,
            CONSTRAINT fk_orden FOREIGN KEY (id_orden) REFERENCES orden (id) ON DELETE CASCADE
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS orden_item');
        $this->addSql('DROP TABLE IF EXISTS product');
        $this->addSql('DROP TABLE IF EXISTS orden');
    }
}
