<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use RuntimeException;
use TeamBlue\ValueObjects\Exception\HostnameException;
use TeamBlue\ValueObjects\Exception\Http\HostnameHttpException;

use function idn_to_ascii;
use function idn_to_utf8;
use function sprintf;

use const IDNA_NONTRANSITIONAL_TO_ASCII;
use const IDNA_NONTRANSITIONAL_TO_UNICODE;

abstract class HostnameValue extends StringValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        $hostName = idn_to_ascii($value, IDNA_NONTRANSITIONAL_TO_ASCII);

        if ($hostName === false) {
            throw static::hostnameException()::noAsciiFormat($value, static::class);
        }

        parent::__construct($hostName);
    }

    /** @return class-string<HostnameException> */
    protected static function hostnameException(): string
    {
        return HostnameHttpException::class;
    }

    public static function example(): static
    {
        return static::fromString('example.com');
    }

    public function toUtf8(): string
    {
        $utf8 = idn_to_utf8($this->toString(), IDNA_NONTRANSITIONAL_TO_UNICODE);

        if ($utf8 === false) {
            throw new RuntimeException(
                sprintf(
                    'Could not convert host name \'%s\' to UTF-8 form',
                    $this->toString(),
                ),
            );
        }

        return $utf8;
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'hostname'];
    }
}
