<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218092245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fight_pokedex_player_one (fight_id INT NOT NULL, pokedex_id INT NOT NULL, INDEX IDX_6F7C8A81AC6657E4 (fight_id), INDEX IDX_6F7C8A81372A5D14 (pokedex_id), PRIMARY KEY(fight_id, pokedex_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fight_pokedex_player_two (fight_id INT NOT NULL, pokedex_id INT NOT NULL, INDEX IDX_4DA8616AC6657E4 (fight_id), INDEX IDX_4DA8616372A5D14 (pokedex_id), PRIMARY KEY(fight_id, pokedex_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fight_pokedex_player_one ADD CONSTRAINT FK_6F7C8A81AC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fight_pokedex_player_one ADD CONSTRAINT FK_6F7C8A81372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fight_pokedex_player_two ADD CONSTRAINT FK_4DA8616AC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fight_pokedex_player_two ADD CONSTRAINT FK_4DA8616372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fight DROP pokedex_player_one, DROP pokedex_player_two');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight_pokedex_player_one DROP FOREIGN KEY FK_6F7C8A81AC6657E4');
        $this->addSql('ALTER TABLE fight_pokedex_player_one DROP FOREIGN KEY FK_6F7C8A81372A5D14');
        $this->addSql('ALTER TABLE fight_pokedex_player_two DROP FOREIGN KEY FK_4DA8616AC6657E4');
        $this->addSql('ALTER TABLE fight_pokedex_player_two DROP FOREIGN KEY FK_4DA8616372A5D14');
        $this->addSql('DROP TABLE fight_pokedex_player_one');
        $this->addSql('DROP TABLE fight_pokedex_player_two');
        $this->addSql('ALTER TABLE fight ADD pokedex_player_one JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD pokedex_player_two JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
