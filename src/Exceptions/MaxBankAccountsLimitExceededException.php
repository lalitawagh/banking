<?php

namespace Kanexy\Banking\Exceptions;

use InvalidArgumentException;

class MaxBankAccountsLimitExceededException extends InvalidArgumentException
{
    public static function create()
    {
        return new static("You have reached the limit of maximum number of bank accounts. Please upgrade your plan to continue.");
    }
}
