<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use RuntimeException;
use Throwable;

final class DatabaseException extends RuntimeException implements ArgonDatabaseException
{
    public static function prepareFailed(string $sql): self
    {
        return new self("Failed to prepare SQL: $sql");
    }

    public static function executionFailed(Throwable $previous): self
    {
        return new self('Statement execution failed', 0, $previous);
    }

    public static function fetchAllFailed(Throwable $previous): self
    {
        return new self('fetchAll() failed', 0, $previous);
    }

    public static function fetchOneFailed(Throwable $previous): self
    {
        return new self('fetchOne() failed', 0, $previous);
    }
}
