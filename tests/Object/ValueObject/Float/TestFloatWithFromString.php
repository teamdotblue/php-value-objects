<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Float;

use TeamBlue\ValueObjects\Implementation\Float\FloatFromString;
use TeamBlue\ValueObjects\Implementation\Float\FloatValue;

class TestFloatWithFromString extends FloatValue
{
    use FloatFromString;
}
