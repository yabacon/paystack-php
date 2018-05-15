<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Subaccount implements RouteInterface
{

    public static function root()
    {
        return '/subaccount';
    }

    public static function create()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Subaccount::root(),
            RouteInterface::PARAMS_KEY => [
                'business_name', 'settlement_bank',
                'account_number', 'percentage_charge',
                'primary_contact_email', 'primary_contact_name',
                'primary_contact_phone',
                'metadata', 'settlement_schedule',
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Subaccount::root() . '/{id}',
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Subaccount::root(),
        ];
    }

    public static function update()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::PUT_METHOD,
            RouteInterface::ENDPOINT_KEY => Subaccount::root() . '/{id}',
            RouteInterface::PARAMS_KEY => [
                'business_name', 'settlement_bank',
                'account_number', 'percentage_charge',
                'primary_contact_email', 'primary_contact_name',
                'primary_contact_phone',
                'metadata', 'settlement_schedule',
            ],
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }
}
