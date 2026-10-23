<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use Override;
use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\ValueObjects\Exception\Context\RangeContext;

class RangeException extends ValueObjectException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Out of range';

    protected RangeContext $context;

    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    #[Override]
    public static function template(): string
    {
        return "The value '{value}' for value object '{valueObject}' " .
            'has to be lower{orEqual} than {maximum} and greater{orEqual} than {minimum}.';
    }

    public static function outsideRange(
        string $value,
        string $class,
        string $minimum,
        string $maximum,
        bool $included,
    ): static {
        $orEqual = $included ? ' or equal' : '';

        return static::fromContextArray(
            [
                'value' => $value,
                'valueObject' => $class,
                'orEqual' => $orEqual,
                'minimum' => $minimum,
                'maximum' => $maximum,
            ],
        );
    }

    public static function outsideRangeFromNumber(
        float|int $value,
        string $class,
        float|int $minimum,
        float|int $maximum,
        bool $included,
    ): static {
        return self::outsideRange(
            (string) $value,
            $class,
            (string) $minimum,
            (string) $maximum,
            $included,
        );
    }
}
