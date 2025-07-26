<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

use Maduser\Argon\Database\ParamStyle;

/**
 * Represents a generic, coroutine-safe DB connection.
 */
interface DatabaseConnectionInterface
{
    public function prepare(string $sql): StatementInterface;

    public function getParamStyle(): ParamStyle;

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}
