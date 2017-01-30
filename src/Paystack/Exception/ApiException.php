<?php

namespace Yabacon\Paystack\Exception;

class ApiException extends PaystackException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
