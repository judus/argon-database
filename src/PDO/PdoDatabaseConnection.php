<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\Contracts\StatementInterface;
use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\ParamStyle;
use PDO;

abstract readonly class PdoDatabaseConnection implements DatabaseConnectionInterface
{
    public function __construct(
        protected PDO $pdo
    ) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $sql): StatementInterface
    {
        $stmt = $this->pdo->prepare($sql);

        if ($stmt === false) {
            throw new DatabaseException("Failed to prepare SQL: $sql");
        }

        return new PdoPreparedStatement($stmt);
    }

    /**
     * @param list<scalar|null> $params
     *
     * @return list<array<string, scalar|null>>
     */
    public function execute(StatementInterface $stmt, array $params): array
    {
        return $stmt->execute($params);
    }

    abstract public function getParamStyle(): ParamStyle;
}
