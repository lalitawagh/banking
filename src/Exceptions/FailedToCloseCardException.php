<?php

namespace Kanexy\Banking\Exceptions;

use InvalidArgumentException;

class FailedToCloseCardException extends InvalidArgumentException
{
    public static function create()
    {
        return new static("Failed to close card.");
    }
}
