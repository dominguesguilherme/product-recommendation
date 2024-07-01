<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240630235217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add orders, order_items, and products tables with appropriate foreign keys';
    }

    public function up(Schema $schema): void
    {
        // Create products table
        $this->addSql('CREATE TABLE products (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            sku VARCHAR(100) NOT NULL,
            name VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_B3BA5A5AF9038C4 (sku),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');

        // Create orders table
        $this->addSql('CREATE TABLE orders (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            amount DOUBLE PRECISION NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');

        // Create order_items table
        $this->addSql('CREATE TABLE order_items (
            id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            order_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
            unit_price DOUBLE PRECISION NOT NULL,
            quantity INT NOT NULL,
            INDEX IDX_62809DB08D9F6D38 (order_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB');

        // Add foreign key constraint
        $this->addSql('ALTER TABLE order_items
            ADD CONSTRAINT FK_62809DB08D9F6D38
            FOREIGN KEY (order_id) REFERENCES orders (id)
        ');
    }

    public function down(Schema $schema): void
    {
        // Drop foreign key constraint
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');

        // Drop tables
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
    }
}
