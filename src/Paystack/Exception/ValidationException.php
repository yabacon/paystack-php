<?php

namespace Yabacon\Paystack\Exception;

class ValidationException extends PaystackException
{
    public $errors;
    public function __construct($message, array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }
}
