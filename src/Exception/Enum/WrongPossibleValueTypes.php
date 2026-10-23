<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Enum;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\EnumValue;
use TeamBlue\ValueObjects\Exception\Context\WrongPossibleValueTypesContext;
use TeamBlue\ValueObjects\Exception\ValueObjectException;

class WrongPossibleValueTypes extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Wrong possible value types';

    protected WrongPossibleValueTypesContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    #[Override]
    public static function template(): string
    {
        return "Enum object '{valueObject}', must have possible values of the type '{correctType}'.";
    }

    /** @param class-string<EnumValue> $class */
    public static function fromClassAndCorrectType(string $class, string $correctType): static
    {
        return static::fromContextArray(
            [
                'valueObject' => $class,
                'correctType' => $correctType,
            ],
        );
    }
}
