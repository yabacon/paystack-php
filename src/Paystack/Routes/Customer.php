<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Customer implements RouteInterface
{

    public static function root()
    {
        return '/customer';
    }

    public static function create()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Customer::root(),
            RouteInterface::PARAMS_KEY => [
                'first_name',
                'last_name',
                'email',
                'metadata',
                'phone',
            ],
            RouteInterface::REQUIRED_KEY => [
                RouteInterface::PARAMS_KEY => [
                    'first_name',
                    'last_name',
                    'email',
                ],
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Customer::root() . '/{id}',
            RouteInterface::ARGS_KEY => ['id'],
            RouteInterface::REQUIRED_KEY => [RouteInterface::ARGS_KEY => ['id']],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Customer::root(),
            RouteInterface::PARAMS_KEY => [
                'perPage',
                'page',
            ],
        ];
    }

    public static function update()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::PUT_METHOD,
            RouteInterface::ENDPOINT_KEY => Customer::root() . '/{id}',
            RouteInterface::PARAMS_KEY => [
                'first_name',
                'last_name',
                'email',
                'metadata',
                'phone',
            ],
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }
}
