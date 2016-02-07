<?php

namespace Paystack\Routes;

use Paystack\Contracts\RouteInterface;

class Plan implements RouteInterface
{

    /**
      Root
     */
    public static function root()
    {
        return '/plan';
    }

    /*
      Create plan
     */

    public static function create()
    {
        return [RouteInterface::METHOD_KEY => 'post',
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
            'currency']
        ];
    }

    /*
      Get plan
     */

    public static function getOne()
    {
        return [RouteInterface::METHOD_KEY => 'get',
          RouteInterface::ENDPOINT_KEY => Plan::root() . '/{id}',
          RouteInterface::ARGS_KEY => ['id']];
    }

    /*
      List plan
     */

    public static function getList()
    {
        return [RouteInterface::METHOD_KEY => 'get',
          RouteInterface::ENDPOINT_KEY => Plan::root()];
    }

    /*
      Update plan
     */

    public static function update()
    {
        return [RouteInterface::METHOD_KEY => 'put',
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
            'currency'],
          RouteInterface::ARGS_KEY => ['id']];
    }
}
