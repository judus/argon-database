<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use PDO;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\Contracts\StatementInterface;
use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\ParamStyle;

abstract readonly class PdoDatabaseConnection implements DatabaseConnectionInterface
{
    public function __construct(
        protected PDO $pdo
    ) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Whoa psalm, dude, of course, it is used.
     *
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function prepare(string $sql): StatementInterface
    {
        $stmt = $this->pdo->prepare($sql);

        if ($stmt === false) {
            throw DatabaseException::prepareFailed($sql);
        }

        return new PdoPreparedStatement($stmt);
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }

    abstract public function getParamStyle(): ParamStyle;
}
