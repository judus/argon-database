<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Exception\ParamParserException;

final class ParamParser
{
    /**
     * @param array<string, scalar> $named
     * @return array{sql: string, params: list<scalar>}
     */
    public function parse(string $sql, array $named, ParamStyle $style = ParamStyle::NUMBERED): array
    {
        if ($style === ParamStyle::NAMED) {
            return ['sql' => $sql, 'params' => $named];
        }

        $params = [];
        $index = 1;

        $sql = preg_replace_callback(
            '/:(\w+)/',
            function (array $match) use (&$params, $named, &$index): string {
                $key = $match[1];

                if (!array_key_exists($key, $named)) {
                    throw new ParamParserException("Missing parameter: :$key");
                }

                $params[] = $named[$key];
                return '$' . $index++;
            },
            $sql
        );

        if (!is_string($sql)) {
            throw new ParamParserException('SQL parameter parsing failed due to malformed regex substitution.');
        }

        return ['sql' => $sql, 'params' => $params];
    }
}
