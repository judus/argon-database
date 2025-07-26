[![PHP](https://img.shields.io/badge/php-8.2+-blue)](https://www.php.net/)
[![Build](https://github.com/judus/argon-database/actions/workflows/php.yml/badge.svg)](https://github.com/judus/argon-database/actions)
[![codecov](https://codecov.io/gh/judus/argon-database/branch/master/graph/badge.svg)](https://codecov.io/gh/judus/argon-database)
[![Psalm Level](https://shepherd.dev/github/judus/argon-database/coverage.svg)](https://shepherd.dev/github/judus/argon-database)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-brightgreen.svg)](https://www.php-fig.org/psr/psr-12/)
[![Latest Version](https://img.shields.io/packagist/v/maduser/argon-database.svg)](https://packagist.org/packages/maduser/argon-database)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

# Argon Database

DBAL wrapper built on top of PDO for PHP 8.2+.

## Features

* Not a ORM
* PDO-based abstraction
* Strict typing everywhere
* DTO hydration
* Supports MySQL, Postgres, SQLite

## Installation

```bash
composer require maduser/argon-database
```

## Creating a Client

Wrap your DB config in a `LazyConnectionProvider`, then pass to `QueryClient`.

```php
use Maduser\Argon\Database\QueryClient;
use Maduser\Argon\Database\PDO\MySQL\MySQLConfig;
use Maduser\Argon\Database\PDO\LazyConnectionProvider;

$provider = new LazyConnectionProvider(
    new MySQLConfig('127.0.0.1', 3306, 'argondb', 'argon', 'secret')
);

$db = new QueryClient($provider);
```

Other drivers:

```php
new PostgresConfig('127.0.0.1', 5432, 'argondb', 'argon', 'secret');
new SqliteConfig(__DIR__ . '/mydb.sqlite');
```

## Query Usage

### `fetchAll()`

```php
$rows = $db
    ->query('SELECT * FROM users WHERE active = :active', ['active' => true])
    ->fetchAll();
```

Returns: `list<array<string, scalar|null>>`

### `fetchOne()`

```php
$user = $db
    ->query('SELECT * FROM users WHERE id = :id', ['id' => 42])
    ->fetchOne();
```

Returns: `array<string, scalar|null> | null`

### `execute()`

```php
$db->query('UPDATE users SET banned = true WHERE id = :id', ['id' => 666])
   ->execute();
```

Returns: `void`

## Using DTOs with `RowMapper`

To hydrate rows into typed objects, implement `RowMapper<T>` on your DTO:

```php
use Maduser\Argon\Database\Contracts\RowMapper;

/**
 * @implements RowMapper<User>
 */
final class User implements RowMapper
{
    public function __construct(
        public readonly int $id,
        public readonly string $email
    ) {}

    public static function map(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            email: (string) $row['email']
        );
    }
}
```

Then hydrate:

```php
/** @var list<User> */
$users = $db
    ->query('SELECT * FROM users')
    ->fetchAllTo(User::class);

/** @var User|null */
$user = $db
    ->query('SELECT * FROM users WHERE id = :id', ['id' => 1])
    ->fetchOneTo(User::class);
```

## Transactions

```php
$result = $db->transaction(function (QueryClient $trx) {
    $trx->query(...)->execute();
    return 'done';
});
```

Commits on success, rolls back on exception.

## License

MIT.
