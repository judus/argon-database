<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use InvalidArgumentException;

final class MapperException extends InvalidArgumentException implements ArgonDatabaseException
{

}