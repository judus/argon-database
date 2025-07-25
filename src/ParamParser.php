<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Exception\ParamParserException;

final class ParamParser
{
    private const REGEX = '/:(\w+)/';

    /**
     * Parses SQL and returns transformed SQL + flat param list for numbered styles.
     *
     * @param array<string, scalar|null> $named
     *
     * @return array{sql: string, params: list<scalar|null>}
     */
    public function parse(string $sql, array $named, ParamStyle $style = ParamStyle::NUMBERED): array
    {
        $paramKeys = $this->extractKeys($sql);

        if ($style === ParamStyle::NAMED) {
            foreach ($paramKeys as $key) {
                if (!array_key_exists($key, $named)) {
                    throw new ParamParserException("Missing parameter: :$key");
                }
            }

            /** @var array<string, scalar|null> $validated */
            $validated = array_intersect_key($named, array_flip($paramKeys));

            return ['sql' => $sql, 'params' => $validated];
        }

        // NUMBERED
        $index = 1;
        $params = [];

        $sql = preg_replace_callback(self::REGEX, function (array $match) use (&$params, $named, &$index): string {
            $key = $match[1];

            if (!array_key_exists($key, $named)) {
                throw new ParamParserException("Missing parameter: :$key");
            }

            $params[] = $named[$key];

            return '$' . $index++;
        }, $sql);

        if (!is_string($sql)) {
            throw new ParamParserException('SQL parameter parsing failed.');
        }

        return ['sql' => $sql, 'params' => $params];
    }

    /**
     * @return list<string>
     */
    public function extractKeys(string $sql): array
    {
        preg_match_all(self::REGEX, $sql, $matches);

        /** @var list<string> */
        return array_unique($matches[1]);
    }
}
