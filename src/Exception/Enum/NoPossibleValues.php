<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception\Enum;

use TeamBlue\ValueObjects\Exception\ValueObjectException;
use TeamBlue\ValueObjects\Implementation\Enum\EnumValue;

use function sprintf;

class NoPossibleValues extends ValueObjectException
{
    /** @param class-string<EnumValue> $class */
    public static function fromClass(string $class): self
    {
        return new self(
            sprintf(
                'The enum \'%s\' has no possible values.',
                $class,
            ),
        );
    }
}
