<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101223408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE reservations_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, common_area_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, hour INT NOT NULL, status VARCHAR(10) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN reservation.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE reservations');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE reservations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reservations (id INT NOT NULL, hour INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, common_area_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN reservations.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE reservation');
    }
}
