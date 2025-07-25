<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\Sqlite;

final readonly class SqliteConfig
{
    public function __construct(
        public string $path
    ) {
    }

    public function dsn(): string
    {
        return 'sqlite:' . $this->path;
    }
}
