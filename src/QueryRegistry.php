<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Exception\QueryNotFoundException;

final class QueryRegistry
{
    /** @var array<string, string> */
    private array $queries = [];

    public function load(string $path): void
    {
        foreach (glob($path . '/*.sql') as $file) {
            $key = basename($file, '.sql');
            $this->queries[$key] = trim(file_get_contents($file));
        }
    }

    public function get(string $key): string
    {
        if (!isset($this->queries[$key])) {
            throw new QueryNotFoundException("Query not found: $key");
        }

        return $this->queries[$key];
    }
}
