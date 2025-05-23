<?php

declare(strict_types=1);

namespace Tests\Mocks;

use Maduser\Argon\Database\Contracts\RowMapper;

/**
 * @implements RowMapper<UserDTO>
 */
final readonly class UserDTO implements RowMapper
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
    ) {}

    public static function map(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            name: (string) $row['name'],
            email: (string) $row['email'],
        );
    }
}
