<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\Contracts\RowMapper;
use Maduser\Argon\Database\Exception\MapperException;

final readonly class QueryRunner
{
    /** @param list<scalar> $params */
    public function __construct(
        private string $sql,
        private array $params,
        private DatabaseConnectionInterface $connection
    ) {
    }

    /** @return list<array<string, null|scalar>> */
    public function fetchAll(): array
    {
        $stmt = $this->connection->prepare($this->sql);

        return $this->connection->execute($stmt, $this->params);
    }

    /** @return array<string, null|scalar>|null */
    public function fetchOne(): ?array
    {
        $rows = $this->fetchAll();
        return $rows[0] ?? null;
    }

    /**
     * @template T
     * @param class-string<RowMapper<T>> $mapper
     * @return list<T>
     */
    public function fetchMapped(string $mapper): array
    {
        if (!is_subclass_of($mapper, RowMapper::class)) {
            throw new MapperException("Expected instance of RowMapper<T>, got $mapper");
        }

        return array_map([$mapper, 'map'], $this->fetchAll());
    }
}
