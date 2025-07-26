<?php

declare(strict_types=1);

namespace Tests;

use Maduser\Argon\Database\Contracts\DatabaseClientInterface;
use Maduser\Argon\Database\ParamParser;
use Maduser\Argon\Database\PDO\MySQL\MySQLConfig;
use Maduser\Argon\Database\PDO\Postgres\PostgresConfig;
use Maduser\Argon\Database\PDO\LazyConnectionProvider;
use Maduser\Argon\Database\PDO\Sqlite\SqliteConfig;
use Maduser\Argon\Database\QueryClient;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

abstract class AbstractDatabaseTestCase extends TestCase
{
    /**
     * @var array<string, DatabaseClientInterface>
     */
    private static array $clients = [];

    protected function getClient(string $driver): DatabaseClientInterface
    {
        if (!isset(self::$clients[$driver])) {
            $provider = match ($driver) {
                'mysql' => new LazyConnectionProvider(
                    new MySQLConfig('127.0.0.1', 3307, 'argondb', 'argon', 'secret')
                ),
                'pgsql' => new LazyConnectionProvider(
                    new PostgresConfig('127.0.0.1', 5432, 'argondb', 'argon', 'secret')
                ),
                'sqlite' => new LazyConnectionProvider(
                    new SqliteConfig(__DIR__ . '/resources/argondb.sqlite')
                ),
                default => throw new InvalidArgumentException("Unsupported driver: $driver"),
            };

            self::$clients[$driver] = new QueryClient(
                $provider,
                new ParamParser()
            );
        }

        return self::$clients[$driver];
    }
}
