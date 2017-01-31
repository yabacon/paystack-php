<?php

namespace Yabacon\Paystack\Exception;

class ApiException extends PaystackException
{
    private $_responseObject;

    public function __construct($message, $object)
    {
        parent::__construct($message);
        $this->_responseObject = $object;
    }

    public function getResponseObject()
    {
        return $this->_responseObject;
    }
}
