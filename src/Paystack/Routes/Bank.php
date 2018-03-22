<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Bank implements RouteInterface
{

    public static function root()
    {
        return '/bank';
    }
    public static function getList()
    {
        return [ RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Bank::root() ];
    }

    public static function resolveBvn()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Bank::root() . '/resolve_bvn/{bvn}',
            RouteInterface::ARGS_KEY     => ['bvn'] ];
    }

    public static function resolve()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Bank::root() . '/resolve',
            RouteInterface::PARAMS_KEY   => ['account_number',
                'bank_code' ] ];
    }
}
