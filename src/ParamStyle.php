<?php

declare(strict_types=1);

namespace Maduser\Argon\Database;

enum ParamStyle: string
{
    case NAMED = 'named';
    case NUMBERED = 'numbered';
}
