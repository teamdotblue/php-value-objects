<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\InvalidUrlException;

class InvalidUrlHttpException extends InvalidUrlException implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
