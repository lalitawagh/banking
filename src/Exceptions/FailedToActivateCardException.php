<?php

namespace Kanexy\Banking\Exceptions;

use InvalidArgumentException;

class FailedToActivateCardException extends InvalidArgumentException
{
    public static function create()
    {
        return new static("Failed to activate card.");
    }
}
