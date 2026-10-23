<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\EmailAsciiFormatException;

class EmailAsciiFormatHttpException extends EmailAsciiFormatException implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
