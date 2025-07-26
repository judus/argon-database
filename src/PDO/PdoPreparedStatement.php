<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use PDO;
use Throwable;
use PDOStatement;
use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\Contracts\StatementInterface;

final readonly class PdoPreparedStatement implements StatementInterface
{
    public function __construct(
        private PDOStatement $statement
    ) {
    }

    /**
     * Executes a non-fetching statement.
     *
     * @param list<scalar|null> $params
     */
    public function execute(array $params): void
    {
        try {
            $this->statement->execute($params);
        } catch (Throwable $e) {
            throw DatabaseException::executionFailed($e);
        }
    }

    /**
     * Executes and returns all rows.
     *
     * @param list<scalar|null> $params
     * @return list<array<string, scalar|null>>
     */
    public function fetchAll(array $params): array
    {
        try {
            $this->statement->execute($params);
        } catch (Throwable $e) {
            throw DatabaseException::fetchAllFailed($e);
        }

        /** @var list<array<string, scalar|null>> */
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes and returns one row.
     *
     * @param list<scalar|null> $params
     * @return array<string, scalar|null>|null
     */
    public function fetchOne(array $params): ?array
    {
        try {
            $this->statement->execute($params);
        } catch (Throwable $e) {
            throw DatabaseException::fetchOneFailed($e);
        }

        $row = $this->statement->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }
}
