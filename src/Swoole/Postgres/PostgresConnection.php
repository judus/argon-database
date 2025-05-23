<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Swoole\Postgres;

use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Swoole\Coroutine\PostgreSQL;
use Swoole\Coroutine\PostgreSQLStatement;

final class PostgresConnection implements DatabaseConnectionInterface
{
    public function __construct(
        private readonly PostgreSQL $conn
    ) {
    }

    public function prepare(string $sql): PostgreSQLStatement|bool
    {
        return $this->conn->prepare($sql);
    }

    public function execute(mixed $stmt, array $params): array|false
    {
        return $this->conn->execute($stmt, $params);
    }
}
