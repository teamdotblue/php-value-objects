<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Bool;

use TeamBlue\ValueObjects\Implementation\Bool\BooleanFromString;
use TeamBlue\ValueObjects\Implementation\Bool\BoolValue;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

class TestBoolWithFromString extends BoolValue
{
    use BooleanFromString;
    use ExamplesLogic;
}
