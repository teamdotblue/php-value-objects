<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\IntBackedEnumValue;

enum IntBackedTestEnum: int
{
    use IntBackedEnumValue;

    case TEST_1 = 1;
    case TEST_2 = 2;
}
