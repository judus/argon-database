<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\Sqlite;

use Maduser\Argon\Database\ParamStyle;
use Maduser\Argon\Database\PDO\PdoDatabaseConnection;

final readonly class SqliteConnection extends PdoDatabaseConnection
{
    public function getParamStyle(): ParamStyle
    {
        return ParamStyle::NAMED;
    }
}