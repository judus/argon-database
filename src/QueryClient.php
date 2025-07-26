<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

use Throwable;
use Maduser\Argon\Database\Contracts\DatabaseClientInterface;
use Maduser\Argon\Database\Contracts\DatabaseConnectionInterface;
use Maduser\Argon\Database\Contracts\ConnectionProviderInterface;
use Maduser\Argon\Database\Contracts\StatementInterface;
use Maduser\Argon\Database\Exception\DatabaseException;
use Maduser\Argon\Database\Exception\QueryNotFoundException;
use Maduser\Argon\Database\Exception\TransactionException;

/**
 * High-level database client.
 *
 * Supports lazy connections and fiber-safe upgrade path.
 */
final readonly class QueryClient implements DatabaseClientInterface
{
    public function __construct(
        private ConnectionProviderInterface $provider,
        private ParamParser $parser = new ParamParser(),
        private ?DatabaseConnectionInterface $connection = null
    ) {
    }

    public function query(string $sql, array $params = []): QueryRunner
    {
        return $this->prepare($sql, $params);
    }

    public function file(string $path, array $params = []): QueryRunner
    {
        if (!is_readable($path)) {
            throw QueryNotFoundException::fileUnreadable($path);
        }

        $sql = file_get_contents($path);
        if (!is_string($sql) || trim($sql) === '') {
            throw QueryNotFoundException::fileEmpty($path);
        }

        return $this->prepare($sql, $params);
    }

    /**
     * @template T
     * @param callable(QueryClient): T $callback
     * @return T
     * @throws Throwable
     */
    public function transaction(callable $callback): mixed
    {
        $connection = $this->connection ?? $this->provider->get();
        $connection->beginTransaction();

        try {
            // Create a new client with scoped connection for the transaction
            $scoped = $this->withConnection($connection);
            $result = $callback($scoped);
            $connection->commit();
            return $result;
        } catch (Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    /**
     * Internal method to scope client to a given connection (for transactions / pooling).
     */
    private function withConnection(DatabaseConnectionInterface $connection): self
    {
        return new self($this->provider, $this->parser, $connection);
    }

    /**
     * Prepares a query and returns a runner.
     *
     * @param array<string, scalar|null> $params
     */
    private function prepare(string $sql, array $params): QueryRunner
    {
        $connection = $this->connection ?? $this->provider->get();
        $parsed = $this->parser->parse($sql, $params, $connection->getParamStyle());

        $stmt = $connection->prepare($parsed['sql']);

        /** @var list<scalar|null> $params */
        $params = $parsed['params'];

        return new QueryRunner($stmt, $params);
    }
}
