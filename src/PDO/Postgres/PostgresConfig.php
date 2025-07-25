<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\Postgres;

final readonly class PostgresConfig
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
        return "pgsql:host=$this->host;port=$this->port;dbname=$this->dbName";
    }
}
