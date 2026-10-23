<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Enum;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\EnumValue;
use TeamBlue\ValueObjects\Exception\Context\NoValidValueContext;
use TeamBlue\ValueObjects\Exception\ValueObjectException;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class NoValidValue extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'No valid value';

    protected NoValidValueContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "The value '{value}' of enum object '{valueObject}' is not valid. Allowed values: {allowedValues}.";
    }

    /** @param class-string<EnumValue> $class */
    public static function fromInvalidValueAndClass(string|int $invalidValue, string $class): static
    {
        return static::fromContextArray(
            [
                'value' => (string) $invalidValue,
                'valueObject' => $class,
                'allowedValues' => json_encode($class::possibleValues(), JSON_THROW_ON_ERROR),
            ],
        );
    }
}
