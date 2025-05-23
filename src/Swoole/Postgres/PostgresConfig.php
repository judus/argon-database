<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Swoole\Postgres;

final class PostgresConfig
{
    public function __construct(
        public readonly string $host,
        public readonly string $dbName,
        public readonly string $user,
        public readonly string $password,
        public readonly int $port = 5432
    ) {
    }

    public function toDSN(): string
    {
        return sprintf(
            'host=%s port=%d dbname=%s user=%s password=%s',
            $this->host,
            $this->port,
            $this->dbName,
            $this->user,
            $this->password
        );
    }
}
