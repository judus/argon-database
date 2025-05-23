<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\MySQL;

final class MySQLConfig
{
    public function __construct(
        public readonly string $host,
        public readonly int $port,
        public readonly string $dbName,
        public readonly string $user,
        public readonly string $password
    ) {}

    public function dsn(): string
    {
        return "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4";
    }
}
