<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\InvalidIpv6Exception;

class InvalidIpv6HttpException extends InvalidIpv6Exception implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
