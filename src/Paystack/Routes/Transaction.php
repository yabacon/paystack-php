<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Transaction implements RouteInterface
{

    public static function root()
    {
        return '/transaction';
    }

    public static function initialize()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/initialize',
            RouteInterface::PARAMS_KEY => [
                'reference',
                'callback_url',
                'amount',
                'email',
                'plan',
            ],
        ];
    }

    public static function charge()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/charge_authorization',
            RouteInterface::PARAMS_KEY => [
                'reference',
                'authorization_code',
                'email',
                'amount',
            ],
        ];
    }

    public static function checkAuthorization()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/check_authorization',
            RouteInterface::PARAMS_KEY => [
                'authorization_code',
                'email',
                'amount',
            ],
        ];
    }

    public static function chargeAuthorization()
    {
        return Transaction::charge();
    }

    public static function chargeToken()
    {
        trigger_error('This endpoint is deprecated!', E_USER_NOTICE);
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/charge_token',
            RouteInterface::PARAMS_KEY => [
                'reference',
                'token',
                'email',
                'amount',
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/{id}',
            RouteInterface::ARGS_KEY => ['id'],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root(),
        ];
    }

    public static function export()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/export',
            RouteInterface::PARAMS_KEY => [
                'from',
                'to',
                'settled',
                'payment_page',
            ],
        ];
    }

    public static function totals()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/totals',
        ];
    }

    public static function verify()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/verify/{reference}',
            RouteInterface::ARGS_KEY => ['reference'],
        ];
    }

    public static function verifyAccessCode()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transaction::root() . '/verify_access_code/{access_code}',
            RouteInterface::ARGS_KEY => ['access_code'],
        ];
    }
}
