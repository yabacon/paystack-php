<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Invoice implements RouteInterface
{
    public static function root()
    {
        return '/paymentrequest';
    }

    public static function create()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root(),
            RouteInterface::PARAMS_KEY => [
                'line_items',
                'description',
                'amount',
                'customer',
                'send_notification',
                'tax',
                'due_date',
                'metadata',
                'draft',
                'currency',
                'has_invoice',
                'invoice_number',
            ],
            RouteInterface::REQUIRED_KEY => [
                RouteInterface::PARAMS_KEY => [
                    'customer',
                    'amount',
                    'due_date',
                ],
            ],
        ];
    }

    public static function fetch()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/{invoice_id_or_code}',
            RouteInterface::ARGS_KEY => ['invoice_id_or_code'],
            RouteInterface::REQUIRED_KEY => [RouteInterface::ARGS_KEY => ['invoice_id_or_code']],
        ];
    }

    public static function getList()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root(),
            RouteInterface::PARAMS_KEY => [
                'currency',
                'customer', 'status', 'paid', 'include_archive',
            ],
        ];
    }

    public static function verify()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/verify/{invoice_id_or_code}',
            RouteInterface::ARGS_KEY => ['invoice_id_or_code'],
            RouteInterface::REQUIRED_KEY => [RouteInterface::ARGS_KEY => ['invoice_id_or_code']],
        ];
    }

    public static function notify()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/notify/{invoice_id_or_code}',
        ];
    }

    public static function metrics()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/totals',
        ];
    }

    public static function finalize()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/finalize/{invoice_id_or_code}',
            RouteInterface::ARGS_KEY => ['invoice_id_or_code'],
            RouteInterface::REQUIRED_KEY => [RouteInterface::ARGS_KEY => ['invoice_id_or_code']],
        ];
    }

    public static function update()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::PUT_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/update/{invoice_id_or_code}',
            RouteInterface::PARAMS_KEY => [
                'line_items',
                'description',
                'amount',
                'customer',
                'send_notification',
                'tax',
                'due_date',
                'metadata',
                'currency',
            ],
        ];
    }

    public static function archive()
    {
        return [
            RouteInterface::METHOD_KEY => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Invoice::root() . '/archive/{invoice_id_or_code}',
        ];
    }
}
