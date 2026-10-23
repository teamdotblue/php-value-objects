<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\Exception\Context\InvalidValueContext;

class Base64EncodedStringException extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Could not base64 decode string';

    protected InvalidValueContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "Could not base64 decode string '{value}' for value object '{valueObject}'.";
    }

    public static function couldNotDecode(string $string, string $class): static
    {
        return static::fromContextArray(
            [
                'value' => $string,
                'valueObject' => $class,
            ],
        );
    }
}
