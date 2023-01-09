<?php

namespace Kanexy\Banking\Exceptions;

use InvalidArgumentException;

class FailedToApproveCardException extends InvalidArgumentException
{
    public static function create()
    {
        return new static("Failed to approve card.");
    }
}
