<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use TeamBlue\ValueObjects\Exception\Http\InvalidUrlHttpException;
use TeamBlue\ValueObjects\Exception\Http\UriAsciiFormatHttpException;
use TeamBlue\ValueObjects\Exception\InvalidUrlException;
use TeamBlue\ValueObjects\Exception\UriAsciiFormatException;

use function filter_var;
use function idn_to_ascii;
use function parse_url;
use function rtrim;
use function str_replace;

use const FILTER_VALIDATE_URL;

abstract class UrlValue extends UriValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        /** @var array{scheme: string, host?: string, path: string}|false $parsedUrl */
        $parsedUrl = parse_url($value);

        if ($parsedUrl === false || ! isset($parsedUrl['host'])) {
            throw static::invalidUrlException()::noValidUrl($value, static::class);
        }

        $host = idn_to_ascii($parsedUrl['host']);

        if ($host === false) {
            throw static::uriAsciiFormatException()::noAsciiFormat($value, static::class);
        }

        $url = str_replace($parsedUrl['host'], $host, $value);

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw static::invalidUrlException()::noValidUrl($value, static::class);
        }

        parent::__construct($url);
    }

    /** @return class-string<InvalidUrlException> */
    protected static function invalidUrlException(): string
    {
        return InvalidUrlHttpException::class;
    }

    /** @return class-string<UriAsciiFormatException> */
    protected static function uriAsciiFormatException(): string
    {
        return UriAsciiFormatHttpException::class;
    }

    public function toStringWithTrailingSlash(): string
    {
        return $this->toStringWithoutTrailingSlash() . '/';
    }

    public function toStringWithoutTrailingSlash(): string
    {
        return rtrim(parent::toString(), '/');
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'uri'];
    }
}
