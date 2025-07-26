<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Contracts;

use Maduser\Argon\Database\Driver;

interface PdoConnectionConfigInterface
{
    public function dsn(): string;

    public function user(): ?string;

    public function password(): ?string;

    public function driver(): Driver;

    /**
     * @return array<int|string, mixed> Options passed directly to PDO constructor.
     *                                  Should include sane defaults if empty.
     */
    public function options(): array;
}
