<?php

namespace Eidetic\Paystack\Routes;

use Eidetic\Paystack\Contracts\RouteInterface;

class Transaction implements RouteInterface
{

    /**
      Root
     */
    public static function root()
    {
        return '/transaction';
    }

    /*
      Initialize transaction
     */

    public static function initialize()
    {
        return [RouteInterface::METHOD_KEY =>  RouteInterface::POST_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root() . '/initialize',
          RouteInterface::PARAMS_KEY => ['reference', 'amount', 'email', 'plan']
        ];
    }

    /*
      Charge authorization
     */

    public static function charge()
    {
        return [RouteInterface::METHOD_KEY =>  RouteInterface::POST_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root() . '/charge_authorization',
          RouteInterface::PARAMS_KEY => ['reference', 'authorization_code', 'email', 'amount']];
    }

    /*
      Charge token
     */

    public static function chargeToken()
    {
        return [RouteInterface::METHOD_KEY =>  RouteInterface::POST_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root() . '/charge_token',
          RouteInterface::PARAMS_KEY => ['reference', 'token', 'email', 'amount']];
    }

    /*
      Get transaction
     */

    public static function getOne()
    {
        return [RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root() . '/{id}',
          RouteInterface::ARGS_KEY => ['id']];
    }

    /*
      List transactions
     */

    public static function getList()
    {
        return [ RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root()];
    }

    /*
      Get totals
     */

    public static function totals()
    {
        return [RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root() . '/totals'];
    }

    /*
      Verify transaction
     */

    public static function verify()
    {
        return [RouteInterface::METHOD_KEY => RouteInterface::GET_METHOD,
          RouteInterface::ENDPOINT_KEY => Transaction::root() . '/verify/{reference}',
          RouteInterface::ARGS_KEY => ['reference']];
    }
}
