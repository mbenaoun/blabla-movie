<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20191225174432
 * @package DoctrineMigrations
 */
final class Version20191225174432 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'This SQL request permits to create `movie` Table.';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE movie (
                    id INT UNSIGNED AUTO_INCREMENT NOT NULL, 
                    title VARCHAR(150) NOT NULL, 
                    poster VARCHAR(255) NOT NULL, 
                    date_insert DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                    date_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE IF EXISTS movie');
    }
}
