<?php

namespace Yabacon\Paystack\Helpers;

use \Closure;
use \Yabacon\Paystack\Contracts\RouteInterface;
use \Yabacon\Paystack\Exception\ValidationException;

class Router
{
    private $route;
    private $route_class;
    private $methods;
    public static $ROUTES = [
        'customer', 'page', 'plan', 'subscription', 'transaction', 'subaccount',
        'balance', 'bank', 'decision', 'integration', 'settlement',
        'transfer', 'transferrecipient'
    ];
    public static $ROUTE_SINGULAR_LOOKUP = [
        'customers'=>'customer',
        'pages'=>'page',
        'plans'=>'plan',
        'subscriptions'=>'subscription',
        'transactions'=>'transaction',
        'banks'=>'bank',
        'settlements'=>'settlement',
        'transfers'=>'transfer',
        'transferrecipients'=>'transferrecipient',
    ];

    const ID_KEY = 'id';
    const PAYSTACK_API_ROOT = 'https://api.paystack.co';

    public function __call($methd, $sentargs)
    {
        $method = ($methd === 'list' ? 'getList' : $methd );
        if (array_key_exists($method, $this->methods) && is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $sentargs);
        } else {
            throw new \Exception('Function "' . $method . '" does not exist for "' . $this->route . '".');
        }
    }

    public static function singularFor($method)
    {
        return (
            array_key_exists($method, Router::$ROUTE_SINGULAR_LOOKUP) ?
                Router::$ROUTE_SINGULAR_LOOKUP[$method] :
                null
            );
    }

    public function __construct($route, $paystackObj)
    {
        if (!in_array($route, Router::$ROUTES)) {
            throw new ValidationException(
                "Route '{$route}' does not exist."
            );
        }

        $this->route = strtolower($route);
        $this->route_class = 'Yabacon\\Paystack\\Routes\\' . ucwords($route);

        $mets = get_class_methods($this->route_class);
        if (empty($mets)) {
            throw new \InvalidArgumentException('Class "' . $this->route . '" does not exist.');
        }
        // add methods to this object per method, except root
        foreach ($mets as $mtd) {
            if ($mtd === 'root') {
                continue;
            }
            $mtdFunc = function (
                array $params = [ ],
                array $sentargs = [ ]
            ) use (
                $mtd,
                $paystackObj
            ) {
                $interface = call_user_func($this->route_class . '::' . $mtd);
                // TODO: validate params and sentargs against definitions
                $caller = new Caller($paystackObj);
                return $caller->callEndpoint($interface, $params, $sentargs);
            };
            $this->methods[$mtd] = \Closure::bind($mtdFunc, $this, get_class());
        }
    }
}
