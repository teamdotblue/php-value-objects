<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Context;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\JsonImmutableObjects\JsonSchemaAwareRecordLogic;

class WrongPossibleValueTypesContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $valueObject;
    private string $correctType;
}
