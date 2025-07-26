<?php

declare(strict_types=1);

namespace Tests\Integration\MySQL;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\Exception\ParamParserException;
use Maduser\Argon\Database\Exception\QueryNotFoundException;
use Maduser\Argon\Database\ParamStyle;
use Maduser\Argon\Database\QueryClient;
use PHPUnit\Framework\MockObject\Exception;
use Tests\AbstractDatabaseTestCase;
use Tests\Mocks\UserDTO;
use Tests\Unit\Mocks\Dummy;

class QueryClientIntegrationTest extends AbstractDatabaseTestCase
{
    public function testQueryExecutesNamedParameterQuery(): void
    {
        $client = $this->getClient('mysql');

        $user = $client->query(
            'SELECT * FROM users WHERE email = :email',
            ['email' => 'alice@example.com']
        )->fetchOne();

        $this->assertNotNull($user);
        $this->assertSame('Alice', $user['name']);
    }

    public function testQueryThrowsOnMissingParam(): void
    {
        $client = $this->getClient('mysql');

        $this->expectException(ParamParserException::class);

        $client->query(
            'SELECT * FROM users WHERE email = :email',
            ['wrong' => 'lol']
        )->fetchOne();
    }

    public function testFileQueryLoadsSqlAndBindsParams(): void
    {
        $client = $this->getClient('mysql');

        $path = __DIR__ . '/../../resources/queries/mysql/find_user_by_email.sql';

        $user = $client->file($path, ['email' => 'alice@example.com'])->fetchOne();

        $this->assertNotNull($user);
        $this->assertSame('Alice', $user['name']);
    }

    public function testFileThrowsIfFileMissing(): void
    {
        $client = $this->getClient('mysql');

        $this->expectException(QueryNotFoundException::class);
        $client->file(__DIR__ . '/../../resources/queries/mysql/this_file_does_not_exist.sql');
    }

    public function testQueryHydratesDto(): void
    {
        $client = $this->getClient('mysql');

        $dto = $client->query(
            'SELECT * FROM users WHERE email = :email',
            ['email' => 'eva@synthetix.space']
        )->fetchOneTo(UserDTO::class);

        $this->assertInstanceOf(UserDTO::class, $dto);
        $this->assertSame('Eva', $dto->name);
        $this->assertSame('eva@synthetix.space', $dto->email);
    }

    public function testTransactionCommits(): void
    {
        $client = $this->getClient('mysql');

        $client->query('DELETE FROM users WHERE email = :email', [
            'email' => 'testuser@example.com',
        ])->execute();

        $result = $client->transaction(function ($trx) {
            $trx->query(
                'INSERT INTO users (name, email) VALUES (:name, :email)',
                ['name' => 'Test User', 'email' => 'testuser@example.com']
            )->execute();

            return true;
        });

        $this->assertTrue($result);

        $user = $client->query(
            'SELECT * FROM users WHERE email = :email',
            ['email' => 'testuser@example.com']
        )->fetchOne();

        $this->assertNotNull($user);
        $this->assertSame('Test User', $user['name']);

        $client->query('DELETE FROM users WHERE email = :email', [
            'email' => 'testuser@example.com',
        ])->execute();
    }

    public function testTransactionRollsBack(): void
    {
        $client = $this->getClient('mysql');

        try {
            $client->transaction(function ($trx) {
                $trx->query(
                    'INSERT INTO users (name, email) VALUES (:name, :email)',
                    ['name' => 'Fail User', 'email' => 'failuser@example.com']
                )->execute();

                throw new \RuntimeException('Trigger rollback');
            });
        } catch (\RuntimeException) {
            // expected
        }

        $user = $client->query(
            'SELECT * FROM users WHERE email = :email',
            ['email' => 'failuser@example.com']
        )->fetchOne();

        $this->assertNull($user, 'User should not exist after rollback');
    }

    public function testFileThrowsIfFileEmpty(): void
    {
        $client = $this->getClient('mysql');

        $emptyFile = tempnam(sys_get_temp_dir(), 'empty_sql_');
        file_put_contents($emptyFile, ''); // empty content

        $this->expectException(QueryNotFoundException::class);
        $this->expectExceptionMessage("SQL file is empty: $emptyFile");

        $client->file($emptyFile);

        unlink($emptyFile);
    }
}
