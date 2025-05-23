<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Swoole\Postgres;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Swoole\Coroutine\PostgreSQL;

final class PostgresProvider implements ConnectionProviderInterface
{
    public function __construct(
        private readonly PostgresConfig $config
    ) {
    }

    public function get(): DatabaseConnectionInterface
    {
        $raw = new PostgreSQL();
        return new PostgresConnection($raw);
    }
}
