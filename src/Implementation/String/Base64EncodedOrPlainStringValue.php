<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use TeamBlue\ValueObjects\Exception\Base64EncodedStringException;
use TeamBlue\ValueObjects\Exception\Http\Base64EncodedStringHttpException;

use function base64_decode;
use function base64_encode;
use function preg_match;

abstract class Base64EncodedOrPlainStringValue extends StringValue
{
    public static function fromPlainString(string $plainString): static
    {
        return static::fromString(base64_encode($plainString));
    }

    public function toPlainString(): string
    {
        if (! $this->isEncoded()) {
            return $this->toString();
        }

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

    public function encode(): static
    {
        if ($this->isEncoded()) {
            return $this;
        }

        return static::fromPlainString($this->toString());
    }

    public function decode(): static
    {
        if (! $this->isEncoded()) {
            return $this;
        }

        return static::fromString($this->toPlainString());
    }

    public function isEncoded(): bool
    {
        return self::isStringEncoded($this->toString());
    }

    public static function isStringEncoded(string $string): bool
    {
        // Check if there are valid base64 characters
        if (! preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) {
            return false;
        }

        // Check if the string is base64 encoded by trying to decode it
        $decoded = base64_decode($string, true);

        // If decoding returns false, it's not a valid base64 encoded string
        if ($decoded === false) {
            return false;
        }

        // Check if encoding the decoded string returns the original string
        return base64_encode($decoded) === $string;
    }
}
