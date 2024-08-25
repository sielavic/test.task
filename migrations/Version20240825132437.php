<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240825132437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql('CREATE TABLE developers (
    id INT AUTO_INCREMENT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    position VARCHAR(50) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    contact_phone VARCHAR(20) DEFAULT NULL,
    PRIMARY KEY(id)
);');
        $this->addSql('CREATE TABLE projects (
    id INT AUTO_INCREMENT NOT NULL,
    title VARCHAR(255) NOT NULL,
    customer VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);');
        $this->addSql('CREATE TABLE developers_projects (
    developer_id INT NOT NULL,
    project_id INT NOT NULL,
    PRIMARY KEY(developer_id, project_id),
    CONSTRAINT FK_developer FOREIGN KEY (developer_id) REFERENCES developers (id) ON DELETE CASCADE,
    CONSTRAINT FK_project FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE
);');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE developers');
        $this->addSql('DROP TABLE projects');
        $this->addSql('ALTER TABLE developers_projects DROP FOREIGN KEY FK_developer');
        $this->addSql('ALTER TABLE developers_projects DROP FOREIGN KEY FK_project');
        $this->addSql('DROP TABLE developers_projects');
    }
}
