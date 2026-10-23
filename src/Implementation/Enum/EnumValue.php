<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use Stringable;
use TeamBlue\ValueObjects\EnumValue as EnumValueInterface;
use TeamBlue\ValueObjects\Exception\Enum\NoPossibleValues;
use TeamBlue\ValueObjects\Exception\Enum\NoValidValue;
use TeamBlue\ValueObjects\Exception\Enum\WrongPossibleValueTypes;
use TeamBlue\ValueObjects\Exception\Http\NoValidValueHttpException;
use TeamBlue\ValueObjects\Exception\Http\WrongPossibleValueTypesHttpException;
use TeamBlue\ValueObjects\HasExamples;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

use function count;
use function in_array;
use function reset;
use function strval;

/** @phpstan-consistent-constructor */
abstract class EnumValue implements EnumValueInterface, HasExamples, ProvidesValidationRules, Stringable
{
    use ExamplesLogic;

    protected int|string $value;

    /** @var int[]|string[] */
    protected array $possibleValues;

    protected function __construct(string|int $value)
    {
        $possibleValues = static::possibleValues();

        if (count($possibleValues) <= 0) {
            throw NoPossibleValues::fromClass(static::class);
        }

        if (! in_array($value, $possibleValues)) {
            throw static::noValidValueException()::fromInvalidValueAndClass($value, static::class);
        }

        $this->value = $value;
        $this->possibleValues = $possibleValues;
    }

    /** @return class-string<NoValidValue> */
    protected static function noValidValueException(): string
    {
        return NoValidValueHttpException::class;
    }

    /** @return class-string<WrongPossibleValueTypes> */
    protected static function wrongPossibleValueTypesException(): string
    {
        return WrongPossibleValueTypesHttpException::class;
    }

    public function __toString(): string
    {
        return strval($this->toValue());
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof static) {
            return false;
        }

        return $this->toValue() === $other->toValue();
    }

    public static function example(): static
    {
        $possibleValues = static::possibleValues();
        $possibleValue = reset($possibleValues);

        if ($possibleValue === false) {
            throw NoPossibleValues::fromClass(static::class);
        }

        return static::fromValue($possibleValue);
    }

    /** @return array<string, array<mixed>> */
    public static function validationRules(): array
    {
        return [
            StringType::ENUM => static::possibleValues(),
        ];
    }
}
