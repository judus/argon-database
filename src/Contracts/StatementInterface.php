<?php
declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

/**
 * Represents a prepared SQL statement.
 */
interface StatementInterface
{
    /**
     * @param list<scalar|null> $params
     *
     * @return list<array<string, scalar|null>>
     */
    public function execute(array $params): array;
}