<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\MySQL;

use PDO;
use Maduser\Argon\Database\Contracts\PdoConnectionConfigInterface;
use Maduser\Argon\Database\Driver;

final readonly class MySQLConfig implements PdoConnectionConfigInterface
{
    public function __construct(
        public string $host,
        public int $port,
        public string $dbName,
        public string $user,
        public string $password
    ) {
    }

    public function dsn(): string
    {
        return "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4";
    }

    public function user(): ?string
    {
        return $this->user;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function driver(): Driver
    {
        return Driver::MYSQL;
    }

    public function options(): array
    {
        return [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        ];
    }
}
