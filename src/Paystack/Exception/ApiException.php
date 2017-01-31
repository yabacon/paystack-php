<?php

namespace Yabacon\Paystack\Exception;

class ApiException extends PaystackException
{
    private $responseObject;

    public function __construct($message, $object)
    {
        parent::__construct($message);
        $this->responseObject = $object;
    }

    public function getResponseObject()
    {
        return $this->responseObject;
    }
}
