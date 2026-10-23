<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Float;

use InvalidArgumentException;

use function is_finite;
use function is_numeric;
use function sprintf;

/** @method static static fromFloat(float $float) */
trait FloatFromString
{
    public static function fromString(string $string): static
    {
        $float = (float) $string;

        if (! is_numeric($string) || ! is_finite($float)) {
            throw new InvalidArgumentException(
                sprintf("Value '%s' is not a numeric value for '%s'.", $string, static::class),
            );
        }

        return static::fromFloat($float);
    }
}
