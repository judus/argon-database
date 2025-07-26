<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use InvalidArgumentException;
use Maduser\Argon\Database\Contracts\RowMapper;
use Maduser\Argon\Database\Contracts\StatementInterface;
use Maduser\Argon\Database\Exception\MapperException;

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

    public function execute(): void
    {
        $this->statement->execute($this->params);
    }

    /**
     * @return list<array<string, scalar|null>>
     */
    public function fetchAll(): array
    {
        return $this->statement->fetchAll($this->params);
    }

    /**
     * @return array<string, scalar|null>|null
     */
    public function fetchOne(): ?array
    {
        return $this->statement->fetchOne($this->params);
    }

    /**
     * @template T
     * @param class-string<RowMapper<T>> $class
     * @return list<T>
     */
    public function fetchAllTo(string $class): array
    {
        if (!is_subclass_of($class, RowMapper::class)) {
            throw MapperException::invalidMapperClass($class);
        }

        return array_map(
            static fn(array $row): mixed => $class::map($row),
            $this->fetchAll()
        );
    }

    /**
     * @template T
     * @param class-string<RowMapper<T>> $class
     * @return T|null
     */
    public function fetchOneTo(string $class): mixed
    {
        if (!is_subclass_of($class, RowMapper::class)) {
            throw MapperException::invalidMapperClass($class);
        }

        $row = $this->fetchOne();
        if (!is_array($row)) {
            return null;
        }

        /** @var T $mapped */
        $mapped = $class::map($row);

        return $mapped;
    }
}
