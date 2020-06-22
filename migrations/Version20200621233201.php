<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200621233201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6064FAF8F53');
        $this->addSql('DROP INDEX IDX_50D0C6064FAF8F53 ON answers');
        $this->addSql('ALTER TABLE answers CHANGE question_id_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_50D0C6061E27F6BF ON answers (question_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6061E27F6BF');
        $this->addSql('DROP INDEX IDX_50D0C6061E27F6BF ON answers');
        $this->addSql('ALTER TABLE answers CHANGE question_id question_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6064FAF8F53 FOREIGN KEY (question_id_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_50D0C6064FAF8F53 ON answers (question_id_id)');
    }
}
