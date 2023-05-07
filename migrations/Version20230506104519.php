<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230506104519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1E977E148 FOREIGN KEY (appartenir_id) REFERENCES emploi (id)');
        $this->addSql('CREATE INDEX IDX_132AD0D1E977E148 ON offre_emploi (appartenir_id)');
        $this->addSql('ALTER TABLE offre_stage ADD appartenir_id INT NOT NULL');
        $this->addSql('ALTER TABLE offre_stage ADD CONSTRAINT FK_955674F2E977E148 FOREIGN KEY (appartenir_id) REFERENCES emploi (id)');
        $this->addSql('CREATE INDEX IDX_955674F2E977E148 ON offre_stage (appartenir_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1E977E148');
        $this->addSql('DROP INDEX IDX_132AD0D1E977E148 ON offre_emploi');
        $this->addSql('ALTER TABLE offre_stage DROP FOREIGN KEY FK_955674F2E977E148');
        $this->addSql('DROP INDEX IDX_955674F2E977E148 ON offre_stage');
        $this->addSql('ALTER TABLE offre_stage DROP appartenir_id');
    }
}
