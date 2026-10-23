<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use BackedEnum;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\Type;
use ReflectionEnum;

use function array_map;

trait BackedEnumValue
{
    public function toValue(): mixed
    {
        return $this->value;
    }

    public static function __schema(): Type
    {
        $values = array_map(
            static fn (BackedEnum $case) => $case->value,
            self::cases(),
        );

        $backingType = (new ReflectionEnum(self::class))->getBackingType();
        $jsonType = $backingType !== null && $backingType->getName() === 'int'
            ? JsonSchema::TYPE_INT
            : JsonSchema::TYPE_STRING;

        return JsonSchema::enum($values, $jsonType);
    }
}
