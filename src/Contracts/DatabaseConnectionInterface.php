<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

use Maduser\Argon\Database\ParamStyle;

/**
 * Represents a generic coroutine-safe DB connection.
 */
interface DatabaseConnectionInterface
{
    /**
     * @param string $sql
     * @return string Prepared statement or identifier
     */
    public function prepare(string $sql): string;

    /**
     * @param string $stmt
     * @param list<scalar> $params
     * @return list<array<string, null|scalar>>
     */
    public function execute(string $stmt, array $params): array;

    public function getParamStyle(): ParamStyle;
}
