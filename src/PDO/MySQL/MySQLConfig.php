<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\MySQL;

final readonly class MySQLConfig
{
    public function __construct(
        public string $host,
        public int $port,
        public string $dbName,
        public string $user,
        public string $password
    ) {}

    public function dsn(): string
    {
        return "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4";
    }
}
