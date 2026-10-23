<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\StringBackedEnumValue;

enum StringBackedTestEnum: string
{
    use StringBackedEnumValue;

    case TEST_1 = 'test1';
    case TEST_2 = 'test2';
}
