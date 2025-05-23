<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Exception\ConnectionUnavailableException;

final class QueryClient
{
    public function __construct(
        private readonly QueryRegistry $registry,
        private readonly ParamParser $parser,
        private readonly ConnectionProviderInterface $pool
    ) {
    }

    /**
     * @param array<string, scalar> $params
     */
    public function run(string $key, array $params = []): QueryRunner
    {
        $sql = $this->registry->get($key);
        $conn = $this->pool->get();

        if (!$conn) {
            throw new ConnectionUnavailableException("No available database connection.");
        }

        $parsed = $this->parser->parse($sql, $params, $conn->getParamStyle());

        return new QueryRunner($parsed['sql'], $parsed['params'], $conn);
    }
}
