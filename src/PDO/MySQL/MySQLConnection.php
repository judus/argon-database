<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\MySQL;

use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\ParamStyle;
use PDO;

final readonly class MySQLConnection implements DatabaseConnectionInterface
{
    public function __construct(
        private PDO $pdo
    ) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $sql): string
    {
        return $sql;
    }

    public function execute(mixed $stmt, array $params): array
    {
        $pdoStmt = $this->pdo->prepare($stmt);
        $pdoStmt->execute($params);

        return $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParamStyle(): ParamStyle
    {
        return ParamStyle::NAMED;
    }
}
