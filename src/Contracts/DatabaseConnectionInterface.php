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
     *
     * @return StatementInterface Prepared statement or identifier
     */
    public function prepare(string $sql): StatementInterface;

    /**
     * @param StatementInterface $stmt
     * @param list<scalar|null> $params
 *
     * @return list<array<string, null|scalar>>
     */
    public function execute(StatementInterface $stmt, array $params): array;

    public function getParamStyle(): ParamStyle;
}
