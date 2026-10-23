<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Int;

use TeamBlue\ValueObjects\Implementation\Int\IntegerFromString;
use TeamBlue\ValueObjects\Implementation\Int\IntValue;

class TestIntWithFromString extends IntValue
{
    use IntegerFromString;
}
