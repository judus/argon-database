<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use RuntimeException;

final class ParamParserException extends RuntimeException implements ArgonDatabaseException
{
    /**
     * @param string $sql
     * @param list<string> $missing
     * @return ParamParserException
     */
    public static function missingParameters(string $sql, array $missing): self
    {
        return new self(sprintf(
            'Missing parameters for SQL: %s — missing: %s',
            $sql,
            implode(', ', array_map(fn(string $k): string => ":$k", $missing))
        ));
    }

    public static function invalidSqlReplacement(string $sql): self
    {
        return new self("SQL parameter parsing failed for query: $sql");
    }
}
