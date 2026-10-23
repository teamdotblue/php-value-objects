<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\Exception\Context\InvalidValueContext;

class HostnameException extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Invalid hostname';

    protected InvalidValueContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "Could not convert host name '{value}' to IDNA ASCII form for value object '{valueObject}'.";
    }

    public static function noAsciiFormat(string $value, string $class): static
    {
        return static::fromContextArray(
            [
                'value' => $value,
                'valueObject' => $class,
            ],
        );
    }
}
