<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use TeamBlue\ValueObjects\Implementation\CalcValue;

use function intval;

/** @method static static from(int $value) */
trait IntBackedEnumValue
{
    use BackedEnumValue;
    use CalcValue;

    public static function fromInt(int $value): static
    {
        return static::from($value);
    }

    public static function fromValue(mixed $value): static
    {
        return static::from(intval($value));
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
