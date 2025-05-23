<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use RuntimeException;

final class ParamParserException extends RuntimeException implements ArgonDatabaseException
{
}