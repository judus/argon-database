<?php

declare(strict_types=1);

namespace Tests\Unit\Mocks;

use Maduser\Argon\Database\ParamStyle;
use Maduser\Argon\Database\PDO\PdoDatabaseConnection;

final readonly class TestableConnection extends PdoDatabaseConnection
{
    public function getParamStyle(): ParamStyle
    {
        return ParamStyle::NAMED;
    }
}
