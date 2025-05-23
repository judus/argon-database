<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\Postgres;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use PDO;

final class PostgresProvider implements ConnectionProviderInterface
{
    public function __construct(
        private readonly PostgresConfig $config
    ) {}

    public function get(): DatabaseConnectionInterface
    {
        return new PostgresConnection(new PDO(
            $this->config->dsn(),
            $this->config->user,
            $this->config->password
        ));
    }
}
