<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Plan implements RouteInterface
{

    public static function root()
    {
        return '/plan';
    }

    public static function create()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Plan::root(),
            RouteInterface::PARAMS_KEY => [
                'name',
                'description',
                'amount',
                'interval',
                'send_invoices',
                'send_sms',
                'hosted_page',
                'hosted_page_url',
                'hosted_page_summary',
                'currency',
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Plan::root() . '/{id}',
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Plan::root(),
        ];
    }

    public static function update()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::PUT_METHOD,
            RouteInterface::ENDPOINT_KEY => Plan::root() . '/{id}',
            RouteInterface::PARAMS_KEY => [
                'name',
                'description',
                'amount',
                'interval',
                'send_invoices',
                'send_sms',
                'hosted_page',
                'hosted_page_url',
                'hosted_page_summary',
                'currency',
            ],
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }
}
