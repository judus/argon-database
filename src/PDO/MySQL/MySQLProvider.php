<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO\MySQL;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use PDO;

final class MySQLProvider implements ConnectionProviderInterface
{
    public function __construct(
        private readonly MySQLConfig $config
    ) {}

    public function get(): DatabaseConnectionInterface
    {
        return new MySQLConnection(new PDO(
            $this->config->dsn(),
            $this->config->user,
            $this->config->password
        ));
    }
}
