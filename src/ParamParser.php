<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Exception\ParamParserException;

final class ParamParser
{
    private const PARAM_REGEX = '/:(\w+)/';

    /**
     * @param array<string, scalar|null> $named
     * @return array{sql: string, params: list<scalar|null>|array<string, scalar|null>}
     */
    public function parse(
        string $sql,
        array $named,
        ParamStyle $style = ParamStyle::NUMBERED,
        ?callable $replacer = null
    ): array {
        $paramKeys = $this->extractKeys($sql);

        $missing = array_diff($paramKeys, array_keys($named));
        if ($missing !== []) {
            throw ParamParserException::missingParameters($sql, array_values($missing));
        }

        if ($style === ParamStyle::NAMED) {
            $validated = array_intersect_key($named, array_flip($paramKeys));
            return ['sql' => $sql, 'params' => $validated];
        }

        $index = 1;
        $replacer ??= $this->defaultReplacer($index);

        return $this->replacePlaceholders($named, $sql, $replacer);
    }

    /**
     * @return array<int<0, max>, string>
     */
    public function extractKeys(string $sql): array
    {
        preg_match_all(self::PARAM_REGEX, $sql, $matches);
        return array_unique($matches[1]);
    }

    /**
     * @param array<string, scalar|null> $named
     * @param string $sql
     * @param callable $replacer
     * @return array{sql: string, params: list<scalar|null>}
     */
    private function replacePlaceholders(
        array $named,
        string $sql,
        callable $replacer
    ): array {
        $params = [];

        $replacedSql = preg_replace_callback(
            self::PARAM_REGEX,
            function (array $match) use (&$params, $named, $replacer): string {
                $key = $match[1];
                $params[] = $named[$key];
                $replacement = $replacer($match);

                if (!is_string($replacement)) {
                    throw ParamParserException::invalidSqlReplacement($match[0]);
                }

                return $replacement;
            },
            $sql
        );

        if (!is_string($replacedSql)) {
            throw ParamParserException::invalidSqlReplacement($sql);
        }

        return ['sql' => $replacedSql, 'params' => $params];
    }

    /**
     * @param int $index
     * @return callable(array): string
     */
    private function defaultReplacer(int &$index): callable
    {
        return function (array $_) use (&$index): string {
            return '$' . $index++;
        };
    }
}
