<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use TeamBlue\ValueObjects\Exception\Base64EncodedStringException;
use TeamBlue\ValueObjects\Exception\Http\Base64EncodedStringHttpException;
use TeamBlue\ValueObjects\Exception\Http\NotBase64EncodedHttpException;
use TeamBlue\ValueObjects\Exception\NotBase64EncodedException;

use function base64_decode;
use function base64_encode;

abstract class Base64EncodedStringValue extends StringValue
{
    protected function __construct(string $value)
    {
        if (! Base64EncodedOrPlainStringValue::isStringEncoded($value)) {
            throw static::notBase64EncodedException()::notEncoded($value, static::class);
        }

        parent::__construct($value);
    }

    /** @return class-string<NotBase64EncodedException> */
    protected static function notBase64EncodedException(): string
    {
        return NotBase64EncodedHttpException::class;
    }

    public static function fromPlainString(string $plainString): static
    {
        return static::fromString(base64_encode($plainString));
    }

    public function toPlainString(): string
    {
        /** @var string|false $decoded */
        $decoded = base64_decode($this->toString());

        if ($decoded === false) {
            throw static::base64EncodedStringException()::couldNotDecode($this->toString(), static::class);
        }

        return $decoded;
    }

    /** @return class-string<Base64EncodedStringException> */
    protected static function base64EncodedStringException(): string
    {
        return Base64EncodedStringHttpException::class;
    }

    public static function example(): static
    {
        return static::fromString('dGVzdA==');
    }
}
