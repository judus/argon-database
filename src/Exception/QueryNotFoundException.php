<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use RuntimeException;

final class QueryNotFoundException extends RuntimeException implements ArgonDatabaseException
{
    public static function fileUnreadable(string $path): self
    {
        return new self("SQL file not found or unreadable: $path");
    }

    public static function fileEmpty(string $path): self
    {
        return new self("SQL file is empty: $path");
    }
}
