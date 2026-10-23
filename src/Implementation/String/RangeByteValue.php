<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use TeamBlue\ValueObjects\Exception\Http\RangeHttpException;
use TeamBlue\ValueObjects\Exception\RangeException;

use const PHP_FLOAT_MAX;
use const PHP_FLOAT_MIN;

abstract class RangeByteValue extends ByteValue
{
    protected function __construct(string $value)
    {
        $floatValue = (float) $value;

        if (
            (static::included() && ($floatValue > static::maximum() || $floatValue < static::minimum()))
            || (! static::included() && ($floatValue >= static::maximum() || $floatValue <= static::minimum()))
        ) {
            throw static::rangeException()::outsideRangeFromNumber(
                $floatValue,
                static::class,
                static::minimum(),
                static::maximum(),
                static::included(),
            );
        }

        parent::__construct($value);
    }

    /** @return class-string<RangeException> */
    protected static function rangeException(): string
    {
        return RangeHttpException::class;
    }

    public static function minimum(): float
    {
        return PHP_FLOAT_MIN;
    }

    public static function maximum(): float
    {
        return PHP_FLOAT_MAX;
    }

    public static function included(): bool
    {
        return true;
    }
}
