<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803131556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_sortie (user_id INT NOT NULL, sortie_id INT NOT NULL, INDEX IDX_596DC8CFA76ED395 (user_id), INDEX IDX_596DC8CFCC72D953 (sortie_id), PRIMARY KEY(user_id, sortie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_sortie ADD CONSTRAINT FK_596DC8CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sortie ADD CONSTRAINT FK_596DC8CFCC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2F6AA94D5');
        $this->addSql('DROP INDEX IDX_3C3FD3F2F6AA94D5 ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE participants_organisateurs_id user_organisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2CBEADE75 FOREIGN KEY (user_organisateur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2CBEADE75 ON sortie (user_organisateur_id)');
        $this->addSql('ALTER TABLE user ADD campus_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF5D55E1 FOREIGN KEY (campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AF5D55E1 ON user (campus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_sortie');
        $this->addSql('ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F2CBEADE75');
        $this->addSql('DROP INDEX IDX_3C3FD3F2CBEADE75 ON sortie');
        $this->addSql('ALTER TABLE sortie CHANGE user_organisateur_id participants_organisateurs_id INT NOT NULL');
        $this->addSql('ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F2F6AA94D5 FOREIGN KEY (participants_organisateurs_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_3C3FD3F2F6AA94D5 ON sortie (participants_organisateurs_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF5D55E1');
        $this->addSql('DROP INDEX IDX_8D93D649AF5D55E1 ON user');
        $this->addSql('ALTER TABLE user DROP campus_id');
    }
}
