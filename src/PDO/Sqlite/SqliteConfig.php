<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\Sqlite;

use Maduser\Argon\Database\Contracts\PdoConnectionConfigInterface;
use Maduser\Argon\Database\Driver;
use PDO;

final readonly class SqliteConfig implements PdoConnectionConfigInterface
{
    public function __construct(
        public string $path
    ) {
    }

    public function dsn(): string
    {
        return 'sqlite:' . $this->path;
    }

    public function user(): ?string
    {
        return null;
    }

    public function password(): ?string
    {
        return null;
    }

    public function driver(): Driver
    {
        return Driver::SQLITE;
    }

    public function options(): array
    {
        return [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        ];
    }
}
