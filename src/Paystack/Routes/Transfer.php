<?php

namespace Yabacon\Paystack\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;

class Transfer implements RouteInterface
{

    public static function root()
    {
        return '/transfer';
    }

    public static function initiate()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root(),
            RouteInterface::PARAMS_KEY   => ['source',
                'amount',
                'currency',
                'reason',
                'recipient' ]
        ];
    }

    public static function finalizeTransfer()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() . '/finalize_transfer',
            RouteInterface::PARAMS_KEY   => ['reference',
                'transfer_code',
                'otp' ] ];
    }

    public static function resendOtp()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() . '/resend_otp',
            RouteInterface::PARAMS_KEY   => ['transfer_code',
                'reason'] ];
    }

    public static function disableOtp()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() . '/disable_otp' ];
    }

    public static function enableOtp()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() . '/enable_otp' ];
    }

    public static function disableOtpFinalize()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::POST_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() . '/disable_otp_finalize',
            RouteInterface::PARAMS_KEY   => ['otp'] ];
    }

    public static function fetch()
    {
        return [RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() . '/{id}',
            RouteInterface::ARGS_KEY     => ['id' ] ];
    }

    public static function getList()
    {
        return [ RouteInterface::METHOD_KEY   => RouteInterface::GET_METHOD,
            RouteInterface::ENDPOINT_KEY => Transfer::root() ];
    }
}
