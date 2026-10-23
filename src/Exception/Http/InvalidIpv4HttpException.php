<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\InvalidIpv4Exception;

class InvalidIpv4HttpException extends InvalidIpv4Exception implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
