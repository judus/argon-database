<?php

declare(strict_types=1);

namespace Maduser\Argon\Database\Exception;

use RuntimeException;

final class QueryNotFoundException extends RuntimeException implements ArgonDatabaseException
{

}