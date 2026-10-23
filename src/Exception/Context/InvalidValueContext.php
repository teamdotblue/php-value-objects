<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Context;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\JsonImmutableObjects\JsonSchemaAwareRecordLogic;

class InvalidValueContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $value;
    private string $valueObject;
}
