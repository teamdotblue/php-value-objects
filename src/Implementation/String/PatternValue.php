<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use TeamBlue\ValueObjects\Exception\Http\PatternHttpException;
use TeamBlue\ValueObjects\Exception\PatternException;
use TeamBlue\ValueObjects\PatternValue as PatternValueInterface;

use function preg_match;
use function sprintf;

abstract class PatternValue extends StringValue implements PatternValueInterface, ProvidesValidationRules
{
    public const BELL = "\x07";

    protected function __construct(string $value)
    {
        if (! static::isValid($value)) {
            throw static::patternException()::noMatch($value, static::pregMatchPattern(), static::class);
        }

        parent::__construct($value);
    }

    /** @return class-string<PatternException> */
    protected static function patternException(): string
    {
        return PatternHttpException::class;
    }

    abstract public static function pattern(): string;

    public static function pregMatchPattern(): string
    {
        return sprintf(self::BELL . '%s' . self::BELL . 'uD', static::pattern());
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::PATTERN => static::pattern()];
    }

    public static function isValid(string $value): bool
    {
        return preg_match(static::pregMatchPattern(), $value) === 1;
    }
}
