<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use function strval;

/** @method static static from(string $value) */
trait StringBackedEnumValue
{
    use BackedEnumValue;

    public static function fromString(string $value): static
    {
        return static::from($value);
    }

    public static function fromValue(mixed $value): static
    {
        return static::from(strval($value));
    }

    public function toString(): string
    {
        return $this->value;
    }
}
