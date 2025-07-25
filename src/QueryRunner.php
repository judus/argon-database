<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Contracts\RowMapper;
use Maduser\Argon\Database\Contracts\StatementInterface;

final readonly class QueryRunner
{
    /**
     * @param list<scalar|null> $params
     */
    public function __construct(
        private StatementInterface $statement,
        private array $params
    ) {
    }

    /** @return list<array<string, scalar|null>> */
    public function fetchAll(): array
    {
        return $this->statement->execute($this->params);
    }

    /** @return array<string, scalar|null>|null */
    public function fetchOne(): ?array
    {
        if (method_exists($this->statement, 'executeOne')) {
            /** @var array<string, scalar|null>|null */
            return $this->statement->executeOne($this->params);
        }

        return $this->fetchAll()[0] ?? null;
    }

    /**
     * @template T
     * @param RowMapper<T> $mapper
     *
     * @return list<T>
     */
    public function fetchMapped(RowMapper $mapper): array
    {
        return array_map(
            fn(array $row): mixed => $mapper->map($row),
            $this->fetchAll()
        );
    }
}
