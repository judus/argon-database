<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\MySQL;

use Maduser\Argon\Database\ParamStyle;
use Maduser\Argon\Database\PDO\PdoDatabaseConnection;

final readonly class MySQLConnection extends PdoDatabaseConnection
{
    public function getParamStyle(): ParamStyle
    {
        return ParamStyle::NAMED;
    }
}
