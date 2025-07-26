<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

enum Driver: string
{
    case MYSQL = 'mysql';
    case POSTGRES = 'pgsql';
    case SQLITE = 'sqlite';
}
