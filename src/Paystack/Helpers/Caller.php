<?php
namespace Yabacon\Paystack\Helpers;

use \Closure;
use \Yabacon\Paystack\Contracts\RouteInterface;
use \Yabacon\Paystack\Http\RequestBuilder;

class Caller
{
    private $paystackObj;

    public function __construct($paystackObj)
    {
        $this->paystackObj = $paystackObj;
    }

    public function callEndpoint($interface, $payload = [ ], $sentargs = [ ])
    {
        $builder = new RequestBuilder($this->paystackObj, $interface, $payload, $sentargs);
        return $builder->build()->send()->wrapUp();
    }
}
