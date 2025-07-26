<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\PDO;

use PDO;
use PDOException;
use Maduser\Argon\Database\Contracts\PdoConnectionConfigInterface;
use Maduser\Argon\Database\Contracts\PdoFactoryInterface;
use Maduser\Argon\Database\Exception\ConnectionException;

final readonly class DefaultPdoFactory implements PdoFactoryInterface
{
    public function __construct(
        private PdoConnectionConfigInterface $config
    ) {
    }

    public function create(): PDO
    {
        try {
            return new PDO(
                $this->config->dsn(),
                $this->config->user(),
                $this->config->password(),
                $this->config->options()
            );
        } catch (PDOException $e) {
            throw ConnectionException::connectionFailed($e);
        }
    }
}
