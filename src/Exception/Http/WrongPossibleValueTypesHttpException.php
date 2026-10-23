<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Http;

use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\ContextHttpAwareExceptionLogic;
use TeamBlue\Exception\HttpException\HttpExceptionInterface;
use TeamBlue\ValueObjects\Exception\Enum\WrongPossibleValueTypes;

class WrongPossibleValueTypesHttpException extends WrongPossibleValueTypes implements HttpExceptionInterface
{
    use ContextHttpAwareExceptionLogic;

    public function getStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
