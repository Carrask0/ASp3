<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318132748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articulo (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, autor VARCHAR(255) NOT NULL, contenido LONGTEXT NOT NULL, creado DATE NOT NULL, categoria VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, autor VARCHAR(255) NOT NULL, contenido LONGTEXT NOT NULL, respuesta INT NOT NULL, articulo_id_id INT NOT NULL, INDEX IDX_4B91E70228F4AF18 (articulo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70228F4AF18 FOREIGN KEY (articulo_id_id) REFERENCES articulo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70228F4AF18');
        $this->addSql('DROP TABLE articulo');
        $this->addSql('DROP TABLE comentario');
    }
}
