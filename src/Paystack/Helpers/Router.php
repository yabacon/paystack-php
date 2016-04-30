<?php

namespace Yabacon\Paystack\Helpers;

use \Closure;
use \Yabacon\Paystack\Contracts\RouteInterface;

class Router
{
    private $route;
    private $route_class;
    private $methods;

    const ID_KEY = 'id';
    const PAYSTACK_API_ROOT = 'https://api.paystack.co';

    public function __call($methd, $sentargs)
    {
        $method = ($methd === 'list' ? 'getList' : $methd );
        if (array_key_exists($method, $this->methods) && is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $sentargs);
        } else {
            // User attempted to call a function that does not exist
            throw new \Exception('Function "' . $method . '" does not exist for "' . $this->route . '".');
        }
    }

    public function __construct($route, $paystackObj)
    {
        $this->route = strtolower($route);
        $this->route_class = 'Yabacon\\Paystack\\Routes\\' . ucwords($route);

        $mets = get_class_methods($this->route_class);
        if (empty($mets)) {
            throw new \InvalidArgumentException('Class "' . $this->route . '" does not exist.');
        }
        // add methods to this object per method, except root
        foreach ($mets as $mtd) {
            if ($mtd === 'root') {
                // skip root method
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
