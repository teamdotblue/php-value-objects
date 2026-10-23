<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Bool;

use InvalidArgumentException;

use function sprintf;
use function strtolower;
use function trim;

/** @method static static fromBool(bool $bool) */
trait BooleanFromString
{
    public static function fromString(string $string): static
    {
        return match (strtolower(trim($string))) {
            '1', 'true' => static::fromBool(true),
            '0', 'false' => static::fromBool(false),
            default => throw new InvalidArgumentException(
                sprintf("Value '%s' is not a boolean value for '%s'.", $string, static::class),
            ),
        };
    }
}
