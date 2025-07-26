<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\Contracts\PdoConnectionConfigInterface;
use Maduser\Argon\Database\Contracts\PdoFactoryInterface;
use Maduser\Argon\Database\Driver;
use PDO;

final class LazyConnectionProvider implements ConnectionProviderInterface
{
    private ?DatabaseConnectionInterface $connection = null;
    private PdoFactoryInterface $pdoFactory;

    public function __construct(
        private readonly PdoConnectionConfigInterface $config,
        ?PdoFactoryInterface $pdoFactory = null
    ) {
        $this->pdoFactory = $pdoFactory ?? new DefaultPdoFactory($config);
    }

    public function get(): DatabaseConnectionInterface
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $pdo = $this->pdoFactory->create();

        $this->connection = match ($this->config->driver()) {
            Driver::MYSQL => new MySQL\MySQLConnection($pdo),
            Driver::POSTGRES => new Postgres\PostgresConnection($pdo),
            Driver::SQLITE => new Sqlite\SqliteConnection($pdo),
        };

        return $this->connection;
    }
}
