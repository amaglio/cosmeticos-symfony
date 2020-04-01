<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227235630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE producto_venta (id INT AUTO_INCREMENT NOT NULL, producto_id_id INT NOT NULL, venta_id_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_CFC0E63F3F63963D (producto_id_id), INDEX IDX_CFC0E63F9F1AB70D (venta_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE producto_venta ADD CONSTRAINT FK_CFC0E63F3F63963D FOREIGN KEY (producto_id_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE producto_venta ADD CONSTRAINT FK_CFC0E63F9F1AB70D FOREIGN KEY (venta_id_id) REFERENCES venta (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE producto_venta');
    }
}
