<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

interface StatementInterface
{
    /**
     * @param list<scalar|null> $params
     */
    public function execute(array $params): void;

    /**
     * @param list<scalar|null> $params
     * @return list<array<string, scalar|null>>
     */
    public function fetchAll(array $params): array;

    /**
     * @param list<scalar|null> $params
     * @return array<string, scalar|null>|null
     */
    public function fetchOne(array $params): ?array;
}
