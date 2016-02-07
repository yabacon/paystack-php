<?php

namespace League\Paystack\Routes;

use League\Paystack\Contracts\RouteInterface;

class Customer implements RouteInterface
{

    /**
      Root
     *
      @param=> first_name, last_name, email, phone
     */
    public static function root()
    {
        return '/customer';
    }

    /**
      Create customer
     *
      @param=> first_name, last_name, email, phone
     */
    public static function create()
    {
        return [League\Paystack\RouteInterface::METHOD_KEY => 'post',
          RouteInterface::ENDPOINT_KEY => Customer::root(),
          RouteInterface::PARAMS_KEY => ['first_name', 'last_name', 'email', 'phone']
        ];
    }

    /**
      Get customer
     */
    public static function getOne()
    {
        return [ RouteInterface::METHOD_KEY => 'get',
          RouteInterface::ENDPOINT_KEY => Customer::root() . '/{id}',
          RouteInterface::ARGS_KEY => ['id']];
    }

    /**
      List customers
     */
    public static function getList()
    {
        return [ RouteInterface::METHOD_KEY => 'get',
          RouteInterface::ENDPOINT_KEY => Customer::root()];
    }

    /**
      Update customer
     *
      @param=> first_name, last_name, email, phone
     */
    public static function update()
    {
        return [ RouteInterface::METHOD_KEY => 'put',
          RouteInterface::ENDPOINT_KEY => Customer::root() . '/{id}',
          RouteInterface::PARAMS_KEY => ['first_name', 'last_name', 'email', 'phone'],
          RouteInterface::ARGS_KEY => ['id']];
    }
}
