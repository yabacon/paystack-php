<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Subscription implements RouteInterface
{

    public static function root()
    {
        return '/subscription';
    }

    public static function create()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root(),
            RouteInterface::PARAMS_KEY => [
                'customer',
                'plan',
                'authorization',
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root() . '/{id}',
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root(),
        ];
    }

    public static function disable()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root() . '/disable',
            RouteInterface::PARAMS_KEY => [
                'code',
                'token',
            ],
        ];
    }

    public static function enable()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Subscription::root() . '/enable',
            RouteInterface::PARAMS_KEY => [
                'code',
                'token',
            ],
        ];
    }
}
