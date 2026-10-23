<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\Exception\Context\PatternContext;

class PatternException extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Pattern mismatch';

    protected PatternContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "The value '{value}' does not match pattern '{pattern}' for value object '{valueObject}'.";
    }

    public static function noMatch(string $value, string $pattern, string $class): static
    {
        return static::fromContextArray(
            [
                'value' => $value,
                'pattern' => $pattern,
                'valueObject' => $class,
            ],
        );
    }
}
