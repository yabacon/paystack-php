<?php

namespace Eidetic\Paystack;

class Paystack
{

    private $secret_key;
    private $routes = ['plan', 'transaction', 'customer'];

    /**
      Secret key
     */
    public function __construct($secret_key)
    {
        $this->secret_key = $secret_key;
    }

    public function __call($method, $args)
    {
        //attempt to call getOne when the route is called directly
        // translates to /{root}/{get}/{id}
        if (in_array($method, $this->routes, true)) {
            $route = new Helpers\Router($method, $this->secret_key);
            // Helpers\Router::put_non_array_values_into_array($args);

            if (count($args) === 1 && is_integer($args[0])) {
                // no params, just one arg... the id
                $args = [[], [Helpers\Router::ID_KEY => $args[0]]];
                return $route->__call('getOne', $args);
            } elseif (count($args) === 2 && is_integer($args[0]) && is_array($args[1])) {
                // there are params, and just one arg... the id
                $args = [$args[1], [Helpers\Router::ID_KEY => $args[0]]];
                return $route->__call('getOne', $args);
            }
        }
    }
    
    public static function registerAutoloader()
    {
        spl_autoload_register(
            function ($class_name) {
                $file = dirname(__FILE__) .DIRECTORY_SEPARATOR;
                $file .= str_replace(['Eidetic\\Paystack\\', '\\'], ['', DIRECTORY_SEPARATOR], $class_name) . '.php';
                // die();
                include_once $file;
            }
        );
    }

    public function __get($name)
    {
        if (in_array($name, $this->routes, true)) {
            return new Helpers\Router($name, $this->secret_key);
        }
    }
}
