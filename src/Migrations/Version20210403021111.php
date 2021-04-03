<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210403021111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE flight_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE place_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE flight (id INT NOT NULL, flight_number INT NOT NULL, departure TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C257E60EA9F64E43 ON flight (flight_number)');
        $this->addSql('COMMENT ON COLUMN flight.departure IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE place (id INT NOT NULL, flight_id INT DEFAULT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_741D53CD91F478C5 ON place (flight_id)');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, place_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C84955DA6A219 ON reservation (place_id)');
        $this->addSql('CREATE INDEX IDX_42C849557E3C61F9 ON reservation (owner_id)');
        $this->addSql('COMMENT ON COLUMN reservation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, owner_id INT DEFAULT NULL, place_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97A0ADA37E3C61F9 ON ticket (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA3DA6A219 ON ticket (place_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email_email VARCHAR(255) NOT NULL, passport_series VARCHAR(255) NOT NULL, passport_number VARCHAR(255) NOT NULL, passport_division VARCHAR(255) NOT NULL, passport_division_code VARCHAR(255) NOT NULL, passport_issue_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, passport_first_name VARCHAR(255) NOT NULL, passport_last_name VARCHAR(255) NOT NULL, passport_middle_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497ADF3DFB ON "user" (email_email)');
        $this->addSql('COMMENT ON COLUMN "user".passport_issue_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD91F478C5 FOREIGN KEY (flight_id) REFERENCES flight (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE place DROP CONSTRAINT FK_741D53CD91F478C5');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955DA6A219');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3DA6A219');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849557E3C61F9');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA37E3C61F9');
        $this->addSql('DROP SEQUENCE flight_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE place_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE "user"');
    }
}
