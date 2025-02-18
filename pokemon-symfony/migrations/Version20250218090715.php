<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218090715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA44566849C08A');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA44563152745');
        $this->addSql('DROP INDEX IDX_21AA44566849C08A ON fight');
        $this->addSql('DROP INDEX IDX_21AA44563152745 ON fight');
        $this->addSql('ALTER TABLE fight ADD pokedex_player_one JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD pokedex_player_two JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP pokedex_player_one_id, DROP pokedex_player_two_id, CHANGE winner winner VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight ADD pokedex_player_one_id INT DEFAULT NULL, ADD pokedex_player_two_id INT DEFAULT NULL, DROP pokedex_player_one, DROP pokedex_player_two, CHANGE winner winner VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44566849C08A FOREIGN KEY (pokedex_player_one_id) REFERENCES pokedex (id)');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA44563152745 FOREIGN KEY (pokedex_player_two_id) REFERENCES pokedex (id)');
        $this->addSql('CREATE INDEX IDX_21AA44566849C08A ON fight (pokedex_player_one_id)');
        $this->addSql('CREATE INDEX IDX_21AA44563152745 ON fight (pokedex_player_two_id)');
    }
}
