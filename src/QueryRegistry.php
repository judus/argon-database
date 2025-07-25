<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Exception\QueryNotFoundException;

final readonly class QueryRegistry
{
    /**
     * @param list<QueryDefinition> $definitions
     */
    public array $definitions;

    public function get(string $key): string
    {
        $definition = $this->definitions[$key] ?? throw new QueryNotFoundException("Query not found: $key");

        return $definition->sql;
    }

    public static function fromPath(string $path): self
    {
        $definitions = [];

        foreach (glob($path . '/*.sql') ?: [] as $file) {
            $definitions[] = QueryDefinition::fromFile($file);
        }

        return new self($definitions);
    }
}
