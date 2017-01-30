<?php

namespace Yabacon\Paystack\Exception;

class PaystackException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
