<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\List;

use TeamBlue\ValueObjects\Implementation\ExamplesLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\ListValue;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestInt;

/** @extends ListValue<TestInt> */
class TestIntList extends ListValue
{
    use ExamplesLogic;
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestInt::class;
    }

    public static function minItems(): int|null
    {
        return 0;
    }

    public static function maxItems(): int|null
    {
        return 20;
    }

    public static function uniqueItems(): bool|null
    {
        return false;
    }
}
