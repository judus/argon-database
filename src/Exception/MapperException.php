<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use InvalidArgumentException;
use Maduser\Argon\Database\Contracts\RowMapper;

final class MapperException extends InvalidArgumentException implements ArgonDatabaseException
{
    public static function invalidMapperClass(string $class): self
    {
        return new self("$class must implement " . RowMapper::class);
    }
}
