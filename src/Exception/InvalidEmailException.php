<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\Exception\Context\InvalidValueContext;

class InvalidEmailException extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Invalid e-mail';

    protected InvalidValueContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "'{value}' is not a valid e-mail for value object '{valueObject}'.";
    }

    public static function noValidEmail(string $value, string $class): static
    {
        return static::fromContextArray(
            [
                'value' => $value,
                'valueObject' => $class,
            ],
        );
    }
}
