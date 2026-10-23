<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\Exception\Context\InvalidValueContext;

class InvalidIpv6Exception extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Invalid ipv6';

    protected InvalidValueContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "'{value}' is not a valid ipv6 for value object '{valueObject}'.";
    }

    public static function noValidIpv6(string $value, string $class): static
    {
        return static::fromContextArray(
            [
                'value' => $value,
                'valueObject' => $class,
            ],
        );
    }
}
