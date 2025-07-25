<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use Maduser\Argon\Database\Contracts\StatementInterface;
use Maduser\Argon\Database\Exception\DatabaseException;
use PDOStatement;
use PDO;
use Throwable;

final readonly class PdoPreparedStatement implements StatementInterface
{
    public function __construct(
        private PDOStatement $statement
    ) {
    }

    /**
     * @param list<scalar|null> $params
     *
     * @return list<array<string, scalar|null>>
     */
    public function execute(array $params): array
    {
        try {
            $this->statement->execute($params);
        } catch (Throwable $e) {
            throw new DatabaseException('Execution failed', 0, $e);
        }

        /** @var list<array<string, scalar|null>> */
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeOne(array $params): ?array
    {
        try {
            $this->statement->execute($params);
        } catch (Throwable $e) {
            throw new DatabaseException('Execution failed', 0, $e);
        }

        /** @var array<string, scalar|null>|false $row */
        $row = $this->statement->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }
}
