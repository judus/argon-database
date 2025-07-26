<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

use Maduser\Argon\Database\QueryRunner;

interface DatabaseClientInterface
{
    /**
     * @param string $sql
     * @param array<string, scalar|null> $params
     */
    public function query(string $sql, array $params = []): QueryRunner;

    /**
     * @param string $path
     * @param array<string, scalar|null> $params
     */
    public function file(string $path, array $params = []): QueryRunner;

    /**
     * @template T
     * @param callable(DatabaseClientInterface): T $callback
     * @return T
     */
    public function transaction(callable $callback): mixed;
}
