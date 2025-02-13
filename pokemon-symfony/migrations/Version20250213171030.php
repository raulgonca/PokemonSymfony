<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213171030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD pokemon_evolution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F38C785A83 FOREIGN KEY (pokemon_evolution_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62DC90F38C785A83 ON pokemon (pokemon_evolution_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F38C785A83');
        $this->addSql('DROP INDEX UNIQ_62DC90F38C785A83 ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP pokemon_evolution_id');
    }
}
