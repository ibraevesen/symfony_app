<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207122234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buy_history (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, amount_paid DOUBLE PRECISION NOT NULL, paypal_transaction_id INT NOT NULL, datetime_paid DATETIME NOT NULL, INDEX IDX_198FEB187975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buy_history ADD CONSTRAINT FK_198FEB187975B7E7 FOREIGN KEY (model_id) REFERENCES car_models (id)');
        $this->addSql('ALTER TABLE car_models ADD model_price DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buy_history DROP FOREIGN KEY FK_198FEB187975B7E7');
        $this->addSql('DROP TABLE buy_history');
        $this->addSql('ALTER TABLE car_models DROP model_price');
    }
}
