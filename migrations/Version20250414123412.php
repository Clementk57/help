<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414123412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE candidacy (id INT AUTO_INCREMENT NOT NULL, offer_id INT NOT NULL, message VARCHAR(255) NOT NULL, file VARCHAR(255) DEFAULT NULL, INDEX IDX_D930569D53C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE candidacy_user (candidacy_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_63D78E4B59B22434 (candidacy_id), INDEX IDX_63D78E4BA76ED395 (user_id), PRIMARY KEY(candidacy_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidacy ADD CONSTRAINT FK_D930569D53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidacy_user ADD CONSTRAINT FK_63D78E4B59B22434 FOREIGN KEY (candidacy_id) REFERENCES candidacy (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidacy_user ADD CONSTRAINT FK_63D78E4BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569D53C674EE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidacy_user DROP FOREIGN KEY FK_63D78E4B59B22434
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidacy_user DROP FOREIGN KEY FK_63D78E4BA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE candidacy
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE candidacy_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE offer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
    }
}
