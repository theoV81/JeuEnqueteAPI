<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200310150040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE suspect CHANGE age age SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE vetement ADD couleur_id INT NOT NULL');
        $this->addSql('ALTER TABLE vetement ADD CONSTRAINT FK_3CB446CFC31BA576 FOREIGN KEY (couleur_id) REFERENCES couleur (id)');
        $this->addSql('CREATE INDEX IDX_3CB446CFC31BA576 ON vetement (couleur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE suspect CHANGE age age SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE vetement DROP FOREIGN KEY FK_3CB446CFC31BA576');
        $this->addSql('DROP INDEX IDX_3CB446CFC31BA576 ON vetement');
        $this->addSql('ALTER TABLE vetement DROP couleur_id');
    }
}
