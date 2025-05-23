<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

interface ConnectionProviderInterface
{
    /**
     * @return DatabaseConnectionInterface
     */
    public function get(): DatabaseConnectionInterface;
}
