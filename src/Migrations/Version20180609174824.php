<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180609174824 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_news (category_id INT NOT NULL, news_id INT NOT NULL, INDEX IDX_9648E31712469DE2 (category_id), INDEX IDX_9648E317B5A459A0 (news_id), PRIMARY KEY(category_id, news_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_news ADD CONSTRAINT FK_9648E31712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_news ADD CONSTRAINT FK_9648E317B5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE author ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C83DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BDAFD8C83DA5256D ON author (image_id)');
        $this->addSql('ALTER TABLE news ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_1DD39950F675F31B ON news (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_news');
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C83DA5256D');
        $this->addSql('DROP INDEX UNIQ_BDAFD8C83DA5256D ON author');
        $this->addSql('ALTER TABLE author DROP image_id');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD39950F675F31B');
        $this->addSql('DROP INDEX IDX_1DD39950F675F31B ON news');
        $this->addSql('ALTER TABLE news DROP author_id');
    }
}
