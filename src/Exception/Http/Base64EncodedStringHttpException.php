<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\Base64EncodedStringException;

class Base64EncodedStringHttpException extends Base64EncodedStringException implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
