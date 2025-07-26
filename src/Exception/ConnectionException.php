<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use RuntimeException;
use Throwable;

final class ConnectionException extends RuntimeException implements ArgonDatabaseException
{
    public static function connectionFailed(Throwable $previous): self
    {
        return new self('Failed to create PDO connection', 0, $previous);
    }
}
