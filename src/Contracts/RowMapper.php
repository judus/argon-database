<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

/**
 * @template T
 */
interface RowMapper
{
    /**
     * @param array<string, scalar|null> $row
     * @return T
     */
    public static function map(array $row): mixed;
}
