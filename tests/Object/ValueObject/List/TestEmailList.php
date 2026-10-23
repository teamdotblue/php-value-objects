<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\List;

use TeamBlue\ValueObjects\Implementation\ExamplesLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\ListValue;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\String\TestEmail;

/** @extends ListValue<TestEmail> */
class TestEmailList extends ListValue
{
    use ExamplesLogic;
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestEmail::class;
    }

    public static function minItems(): int|null
    {
        return 1;
    }

    public static function maxItems(): int|null
    {
        return 10;
    }

    public static function uniqueItems(): bool|null
    {
        return true;
    }
}
