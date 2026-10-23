<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use TeamBlue\ValueObjects\Exception\EmailAsciiFormatException;
use TeamBlue\ValueObjects\Exception\Http\EmailAsciiFormatHttpException;
use TeamBlue\ValueObjects\Exception\Http\InvalidEmailHttpException;
use TeamBlue\ValueObjects\Exception\InvalidEmailException;

use function filter_var;
use function idn_to_ascii;

use const FILTER_VALIDATE_EMAIL;
use const IDNA_DEFAULT;
use const INTL_IDNA_VARIANT_UTS46;

abstract class EmailValue extends StringValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        $email = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

        if ($email === false) {
            throw static::emailAsciiFormatException()::noAsciiFormat($value, static::class);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw static::invalidEmailException()::noValidEmail($value, static::class);
        }

        parent::__construct($email);
    }

    /** @return class-string<EmailAsciiFormatException> */
    protected static function emailAsciiFormatException(): string
    {
        return EmailAsciiFormatHttpException::class;
    }

    /** @return class-string<InvalidEmailException> */
    protected static function invalidEmailException(): string
    {
        return InvalidEmailHttpException::class;
    }

    public static function example(): static
    {
        return static::fromString('test@test.be');
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'email'];
    }
}
