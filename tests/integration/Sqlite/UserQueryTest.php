<?php

declare(strict_types=1);

namespace Tests\Integration\Sqlite;

use Tests\AbstractDatabaseTestCase;
use Tests\Mocks\UserDTO;

class UserQueryTest extends AbstractDatabaseTestCase
{
    public function testFindUserByEmail(): void
    {
        $client = $this->getClient('sqlite');
        $dir = __DIR__ . '/../../resources/queries/sqlite/';

        $runner = $client->file($dir . 'find_user_by_email.sql', ['email' => 'alice@example.com']);

        $user = $runner->fetchOne();

        $this->assertNotNull($user);
        $this->assertSame('Alice', $user['name']);
    }

    public function testFetchMappedReturnsHydratedDTOs(): void
    {
        $client = $this->getClient('sqlite');
        $dir = __DIR__ . '/../../resources/queries/sqlite/';

        $runner = $client->file($dir . 'find_all_users.sql');

        $users = $runner->fetchAllTo(UserDTO::class);

        $this->assertNotEmpty($users);
        $this->assertCount(5, $users);

        foreach ($users as $user) {
            $this->assertInstanceOf(UserDTO::class, $user);
        }

        $this->assertSame('Steve', $users[0]->name);
        $this->assertSame('steve@example.com', $users[0]->email);
        $this->assertSame('Eva', $users[3]->name);
        $this->assertSame('eva@synthetix.space', $users[3]->email);
    }
}
