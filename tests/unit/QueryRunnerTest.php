<?php

declare(strict_types=1);

namespace Tests\Unit;

use Maduser\Argon\Database\Contracts\RowMapper;
use Maduser\Argon\Database\Contracts\StatementInterface;
use Maduser\Argon\Database\Exception\MapperException;
use Maduser\Argon\Database\QueryRunner;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Mocks\UserDTO;
use Tests\Unit\Mocks\Dummy;

final class QueryRunnerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFetchAllToMapsResults(): void
    {
        $stmt = $this->createMock(StatementInterface::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'Steve', 'email' => 'steve@example.com']
        ]);

        $runner = new QueryRunner($stmt, []);
        $result = $runner->fetchAllTo(UserDTO::class);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(UserDTO::class, $result[0]);
    }

    /**
     * @throws Exception
     */
    public function testFetchOneToMapsSingleResult(): void
    {
        $stmt = $this->createMock(StatementInterface::class);
        $stmt->method('fetchOne')->willReturn(['id' => 2, 'name' => 'Alice', 'email' => 'alice@example.com']);

        $runner = new QueryRunner($stmt, []);
        $result = $runner->fetchOneTo(UserDTO::class);

        $this->assertInstanceOf(UserDTO::class, $result);
        $this->assertSame('Alice', $result->name);
    }

    /**
     * @throws Exception
     */
    public function testFetchOneToReturnsNullWhenNoData(): void
    {
        $stmt = $this->createMock(StatementInterface::class);
        $stmt->method('fetchOne')->willReturn(null);

        $runner = new QueryRunner($stmt, []);
        $result = $runner->fetchOneTo(UserDTO::class);

        $this->assertNull($result);
    }

    /**
     * @throws Exception
     */
    public function testFetchToThrowsOnInvalidMapper(): void
    {
        $stmt = $this->createMock(StatementInterface::class);
        $stmt->method('fetchOne')->willReturn(['id' => 1]);

        $runner = new QueryRunner($stmt, []);

        $this->expectException(MapperException::class);

        /** @psalm-suppress InvalidArgument (intentional) */
        $runner->fetchOneTo(stdClass::class);
    }

    /**
     * @throws Exception
     */
    public function testFetchOneToThrowsIfNotRowMapper(): void
    {
        $statement = $this->createMock(StatementInterface::class);
        $statement->method('fetchOne')->willReturn(['name' => 'Alice']);

        $runner = new QueryRunner($statement, []);

        $this->expectException(MapperException::class);
        $this->expectExceptionMessage(Dummy::class . ' must implement ' . RowMapper::class);

        /** @psalm-suppress InvalidArgument (intentional) */
        $runner->fetchOneTo(Dummy::class);
    }

    /**
     * @throws Exception
     */
    public function testFetchAkkToThrowsIfNotRowMapper(): void
    {
        $statement = $this->createMock(StatementInterface::class);
        $statement->method('fetchAll')->willReturn([['name' => 'Alice']]);

        $runner = new QueryRunner($statement, []);

        $this->expectException(MapperException::class);
        $this->expectExceptionMessage(Dummy::class . ' must implement ' . RowMapper::class);

        /** @psalm-suppress InvalidArgument (intentional) */
        $runner->fetchAllTo(Dummy::class);
    }
}
