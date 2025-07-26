<?php

declare(strict_types=1);

namespace Tests\Unit;

use Maduser\Argon\Database\Contracts\PdoConnectionConfigInterface;
use Maduser\Argon\Database\Contracts\PdoFactoryInterface;
use Maduser\Argon\Database\Driver;
use Maduser\Argon\Database\PDO\LazyConnectionProvider;
use Maduser\Argon\Database\PDO\MySQL\MySQLConnection;
use Maduser\Argon\Database\PDO\Postgres\PostgresConnection;
use Maduser\Argon\Database\PDO\Sqlite\SqliteConnection;
use PDO;
use PDOException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class LazyConnectionProviderTest extends TestCase
{
    /**
     * @throws Exception
     */
    private function mockProviderWithPdo(PDO $pdo, Driver $driver): LazyConnectionProvider
    {
        $config = $this->createStub(PdoConnectionConfigInterface::class);
        $config->method('driver')->willReturn($driver);

        $factory = $this->createStub(PdoFactoryInterface::class);
        $factory->method('create')->willReturn($pdo);

        return new LazyConnectionProvider($config, $factory);
    }

    /**
     * @throws Exception
     */
    public function testGetReturnsMySQLConnection(): void
    {
        $pdo = $this->createMock(PDO::class);
        $provider = $this->mockProviderWithPdo($pdo, Driver::MYSQL);

        $connection = $provider->get();

        $this->assertInstanceOf(MySQLConnection::class, $connection);
    }

    /**
     * @throws Exception
     */
    public function testGetReturnsPostgresConnection(): void
    {
        $pdo = $this->createMock(PDO::class);
        $provider = $this->mockProviderWithPdo($pdo, Driver::POSTGRES);

        $connection = $provider->get();

        $this->assertInstanceOf(PostgresConnection::class, $connection);
    }

    /**
     * @throws Exception
     */
    public function testGetReturnsSqliteConnection(): void
    {
        $pdo = $this->createMock(PDO::class);
        $provider = $this->mockProviderWithPdo($pdo, Driver::SQLITE);

        $connection = $provider->get();

        $this->assertInstanceOf(SqliteConnection::class, $connection);
    }

    /**
     * @throws Exception
     */
    public function testGetReturnsSameInstance(): void
    {
        $pdo = $this->createMock(PDO::class);
        $provider = $this->mockProviderWithPdo($pdo, Driver::SQLITE);

        $first = $provider->get();
        $second = $provider->get();

        $this->assertSame($first, $second);
    }

    /**
     * @throws Exception
     */
    public function testCreatePdoThrowsConnectionException(): void
    {
        $config = $this->createStub(PdoConnectionConfigInterface::class);
        $config->method('driver')->willReturn(Driver::MYSQL);
        $config->method('dsn')->willReturn('mysql:host=localhost;port=3306;dbname=nonexistent');
        $config->method('user')->willReturn('invalid_user');
        $config->method('password')->willReturn('invalid_pass');
        $config->method('options')->willReturn([]);

        $provider = new LazyConnectionProvider($config);

        $this->expectException(\Maduser\Argon\Database\Exception\ConnectionException::class);
        $provider->get();
    }
}
