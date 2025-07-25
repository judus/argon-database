<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Exception\ParamParserException;
use RuntimeException;

final readonly class QueryDefinition
{
    /**
     * @param list<string> $keys
     */
    public function __construct(
        public string $key,
        public string $sql,
        public ?string $filePath,
        public array $keys
    ) {
    }

    /**
     * @return list<string>
     */
    public function requiredParams(): array
    {
        return $this->keys;
    }

    /**
     * @param array<string, mixed> $input
     * @param bool                 $strictExtra If true, extra keys not in query will trigger exception
     *
     * @throws ParamParserException
     */
    public function validateParams(array $input, bool $strictExtra = true): void
    {
        $missing = array_diff($this->keys, array_keys($input));
        if ($missing !== []) {
            throw new ParamParserException(sprintf(
                "Missing required parameters: [%s]",
                implode(', ', $missing)
            ));
        }

        if ($strictExtra) {
            $extra = array_diff(array_keys($input), $this->keys);
            if ($extra !== []) {
                throw new ParamParserException(sprintf(
                    "Unexpected parameters: [%s]",
                    implode(', ', $extra)
                ));
            }
        }

        foreach ($input as $key => $value) {
            if (!in_array($key, $this->keys, true)) {
                continue; // already handled by strictExtra
            }

            if (!is_scalar($value) && $value !== null) {
                throw new ParamParserException("Invalid value for :$key — must be scalar or null.");
            }
        }
    }

    public static function fromFile(string $filePath): self
    {
        $sql = file_get_contents($filePath);
        if (!is_string($sql) || trim($sql) === '') {
            throw new RuntimeException("Invalid or empty SQL in file: $filePath");
        }

        $key = basename($filePath, '.sql');
        $keys = (new ParamParser())->extractKeys($sql);

        return new self($key, $sql, $filePath, $keys);
    }
}
