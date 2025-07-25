<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use PDO;

final class PdoConnectionProvider implements ConnectionProviderInterface
{
    private ?DatabaseConnectionInterface $connection = null;

    public function __construct(
        private readonly PdoConnectionConfig $config
    ) {
    }

    public function get(): DatabaseConnectionInterface
    {
        return $this->connection ??= match ($this->config->driver) {
            Driver::MYSQL => new MySQLConnection($this->createPdo()),
            Driver::POSTGRES => new PostgresConnection($this->createPdo()),
            Driver::SQLITE => new SqliteConnection($this->createPdo()),
        };
    }

    private function createPdo(): PDO
    {
        return new PDO(
            $this->config->dsn(),
            $this->config->user,
            $this->config->password
        );
    }
}
