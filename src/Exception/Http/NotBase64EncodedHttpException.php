<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\NotBase64EncodedException;

class NotBase64EncodedHttpException extends NotBase64EncodedException implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;
}
