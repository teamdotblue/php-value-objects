<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Int;

use InvalidArgumentException;

use function preg_match;
use function sprintf;
use function trim;

/** @method static static fromInt(int $integer) */
trait IntegerFromString
{
    public static function fromString(string $string): static
    {
        $integer = trim($string);

        if (preg_match('/^[+-]?\d+$/', $integer) !== 1) {
            throw new InvalidArgumentException(
                sprintf("Value '%s' is not a numeric value for '%s'.", $string, static::class),
            );
        }

        return static::fromInt((int) $integer);
    }
}
