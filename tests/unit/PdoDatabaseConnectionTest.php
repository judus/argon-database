<?php

declare(strict_types=1);

namespace Tests\Unit;

use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\PDO\PdoDatabaseConnection;
use PDO;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Maduser\Argon\Database\ParamStyle;
use Tests\Unit\Mocks\TestableConnection;

final class PdoDatabaseConnectionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testPrepareThrowsPrepareFailed(): void
    {
        $pdo = $this->createMock(PDO::class);
        $pdo->method('setAttribute')->willReturn(true);
        $pdo->method('prepare')->willReturn(false);

        $conn = new TestableConnection($pdo);

        $sql = 'SELECT * FROM nowhere';

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage("Failed to prepare SQL: $sql");

        $stmt = $conn->prepare($sql);
    }
}
