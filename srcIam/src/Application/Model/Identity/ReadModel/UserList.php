<?php declare(strict_types=1);

namespace IdentityAccess\Application\Model\Identity\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionDelete;
use Ecotone\EventSourcing\Attribute\ProjectionInitialization;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use IdentityAccess\Application\Model\Identity\Event\UserWasRegistered;
use IdentityAccess\Application\Model\Identity\User;

#[Projection('UserList', User::class)]
class UserList
{
    public const GET_USER_LIST = "getUserList";

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[EventHandler]
    public function addUser(UserWasRegistered $event, array $metadata): void
    {
        $result = $this->connection->executeStatement(<<<SQL
    INSERT INTO users VALUES (?,?,?)
SQL, [$event->getUserId(), $event->getEmail(), $event->getHashedPassword()]);
    }

    #[ProjectionInitialization]
    public function initialization(): void
    {
        $this->connection->executeStatement(<<<SQL
    CREATE TABLE IF NOT EXISTS users (
        user_id VARCHAR(36) PRIMARY KEY,
        email VARCHAR(25),
        password VARCHAR(200)
    )
SQL);
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $this->connection->executeStatement(<<<SQL
    DELETE FROM users
SQL);
    }

    #[ProjectionDelete]
    public function delete(): void
    {
        $this->connection->executeStatement(<<<SQL
    DROP TABLE users
SQL);
    }

    #[QueryHandler(self::GET_USER_LIST)]
    public function getUserList(): array
    {
        try {
            return $this->connection->executeQuery(
                <<<SQL
    SELECT * FROM users
SQL
            )->fetchAllAssociative();
        } catch (TableNotFoundException) {
            return [];
        }
    }
}
