<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;

final readonly class QueryClient
{
    public function __construct(
        private QueryRegistry $registry,
        private ParamParser $parser,
        private ConnectionProviderInterface $pool
    ) {
    }

    /**
     * @param array<string, scalar|null> $params
     */
    public function run(string $key, array $params = []): QueryRunner
    {
        $sql = $this->registry->get($key);
        $connection = $this->pool->get();

        $parsed = $this->parser->parse($sql, $params, $connection->getParamStyle());
        $stmt = $connection->prepare($parsed['sql']);

        return new QueryRunner($stmt, $parsed['params']);
    }
}
