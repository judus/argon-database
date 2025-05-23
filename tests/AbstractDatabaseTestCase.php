<?php

declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use Maduser\Argon\Database\ParamParser;
use Maduser\Argon\Database\PDO\MySQL\MySQLConfig;
use Maduser\Argon\Database\PDO\MySQL\MySQLProvider;
use Maduser\Argon\Database\PDO\Postgres\PostgresConfig;
use Maduser\Argon\Database\PDO\Postgres\PostgresProvider;
use Maduser\Argon\Database\QueryClient;
use Maduser\Argon\Database\QueryRegistry;
use PHPUnit\Framework\TestCase;

abstract class AbstractDatabaseTestCase extends TestCase
{
    /**
     * @var array<string, QueryClient>
     */
    private static array $clients = [];

    protected function getClient(string $driver): QueryClient
    {
        if (!isset(self::$clients[$driver])) {
            $basePath = __DIR__ . '/resources/queries';
            $queryPath = "$basePath/$driver";

            $registry = new QueryRegistry();
            $registry->load($queryPath);
            $parser = new ParamParser();

            $provider = match ($driver) {
                'mysql' => new MySQLProvider(new MySQLConfig('127.0.0.1', 3307, 'argondb', 'argon', 'secret')),
                'pgsql' => new PostgresProvider(new PostgresConfig('127.0.0.1', 5432, 'argondb', 'argon', 'secret')),
                default => throw new InvalidArgumentException("Unsupported driver: $driver"),
            };

            self::$clients[$driver] = new QueryClient($registry, $parser, $provider);
        }

        return self::$clients[$driver];
    }
}
