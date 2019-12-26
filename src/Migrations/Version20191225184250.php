<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20191225184250
 * @package DoctrineMigrations
 */
final class Version20191225184250 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'This SQL Request permits to map `movie_id` key in `users_movies` table with the `id` key in `movie` table';
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
            'ALTER TABLE users_movies ADD CONSTRAINT fk_users_movies_movie FOREIGN KEY (movie_id) REFERENCES `movie`(id);'
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

        $this->addSql('ALTER TABLE users_movies DROP FOREIGN KEY fk_users_movies_movie;');
    }
}
