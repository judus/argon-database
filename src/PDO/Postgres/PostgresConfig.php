<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\Postgres;

final class PostgresConfig
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
        return "pgsql:host=$this->host;port=$this->port;dbname=$this->dbName";
    }
}
