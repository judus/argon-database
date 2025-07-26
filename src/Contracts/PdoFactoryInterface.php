<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

use PDO;

interface PdoFactoryInterface
{
    public function create(): PDO;
}
