<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20191225184248
 * @package DoctrineMigrations
 */
final class Version20191225184248 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'This SQL Request permits to map `user_id` key in `users_movies` table with the `id` key in `user` table';
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
            'ALTER TABLE users_movies ADD CONSTRAINT fk_users_movies_user FOREIGN KEY (user_id) REFERENCES `user`(id);'
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

        $this->addSql('ALTER TABLE users_movies DROP FOREIGN KEY fk_users_movies_user;');
    }
}
