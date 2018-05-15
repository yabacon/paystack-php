<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Page implements RouteInterface
{

    public static function root()
    {
        return '/page';
    }

    public static function create()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root(),
            RouteInterface::PARAMS_KEY => [
                'name', 'description',
                'amount',
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root() . '/{id}',
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root(),
        ];
    }

    public static function update()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::PUT_METHOD,
            RouteInterface::ENDPOINT_KEY => Page::root() . '/{id}',
            RouteInterface::PARAMS_KEY => [
                'name',
                'description',
                'amount',
                'active',
            ],
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }
}
