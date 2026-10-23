<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\UriNoTrailingSlashException;

class UriNoTrailingSlashHttpException extends UriNoTrailingSlashException implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
