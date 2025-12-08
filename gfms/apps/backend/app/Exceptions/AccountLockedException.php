<?php

namespace App\Exceptions;

use Exception;

class AccountLockedException extends Exception
{
    public function __construct(string $message = 'Account is locked due to multiple failed login attempts', int $code = 423)
    {
        parent::__construct($message, $code);
    }
}
