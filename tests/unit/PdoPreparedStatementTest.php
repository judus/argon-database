<?php

declare(strict_types=1);

namespace Tests\Unit;

use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\PDO\PdoPreparedStatement;
use PDOStatement;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PdoPreparedStatementTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testExecuteThrowsExecutionFailed(): void
    {
        $pdoStmt = $this->createMock(PDOStatement::class);
        $pdoStmt->method('execute')->willThrowException(new RuntimeException('boom'));

        $stmt = new PdoPreparedStatement($pdoStmt);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Statement execution failed');

        $stmt->execute(['x']);
    }


    /**
     * @throws Exception
     */
    public function testFetchAllThrowsFetchAllFailed(): void
    {
        $pdoStmt = $this->createMock(PDOStatement::class);
        $pdoStmt->method('execute')->willThrowException(new RuntimeException('explode'));

        $stmt = new PdoPreparedStatement($pdoStmt);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('fetchAll() failed');

        $stmt->fetchAll(['y']);
    }

    /**
     * @throws Exception
     */
    public function testFetchOneThrowsFetchOneFailed(): void
    {
        $pdoStmt = $this->createMock(PDOStatement::class);
        $pdoStmt->method('execute')->willThrowException(new RuntimeException('fail'));

        $stmt = new PdoPreparedStatement($pdoStmt);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('fetchOne() failed');

        $stmt->fetchOne(['z']);
    }
}
