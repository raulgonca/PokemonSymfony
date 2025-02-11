<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211155819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fight (id INT AUTO_INCREMENT NOT NULL, user_pokedex_id INT DEFAULT NULL, enemy_pokemon_id INT DEFAULT NULL, winner VARCHAR(255) NOT NULL, INDEX IDX_21AA4456904D9BF8 (user_pokedex_id), INDEX IDX_21AA4456DF87FBF9 (enemy_pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokedex (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, pokemon_id INT DEFAULT NULL, pokemon_level INT NOT NULL, pokemon_strength INT NOT NULL, INDEX IDX_6336F6A7A76ED395 (user_id), INDEX IDX_6336F6A72FE71C3E (pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA4456904D9BF8 FOREIGN KEY (user_pokedex_id) REFERENCES pokedex (id)');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA4456DF87FBF9 FOREIGN KEY (enemy_pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A72FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA4456904D9BF8');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA4456DF87FBF9');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A7A76ED395');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A72FE71C3E');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE pokedex');
        $this->addSql('DROP TABLE pokemon');
    }
}
