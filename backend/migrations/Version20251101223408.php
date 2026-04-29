<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20251101223408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Reservation table with sequence';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('CREATE TABLE reservation (
            id INT NOT NULL PRIMARY KEY,
            common_area_id INT NOT NULL,
            date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            hour INT NOT NULL,
            status VARCHAR(10) NOT NULL
        )');

        $this->addSql('COMMENT ON COLUMN reservation.date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
    }
}
