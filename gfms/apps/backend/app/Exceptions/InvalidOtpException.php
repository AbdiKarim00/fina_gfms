<?php

namespace App\Exceptions;

use Exception;

class InvalidOtpException extends Exception
{
    public function __construct(string $message = 'Invalid or expired OTP', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
