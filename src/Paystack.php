<?php

namespace Paystack;

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
            $route = new Helpers\Route($method, $this->secret_key);
            // Helpers\Route::put_non_array_values_into_array($args);

            if (count($args) === 1 && is_integer($args[0])) {
                // no params, just one arg... the id
                $args = [[], [Helpers\Route::ID_KEY => $args[0]]];
                return $route->__call('getOne', $args);
            }
        }
    }
    
    public static function registerAutoloader()
    {
        spl_autoload_register(
            function ($class_name) {
                include_once str_replace(['League\\Paystack\\', '\\'], ['', DIRECTORY_SEPARATOR], $class_name) . '.php';
            }
        );
    }

    public function __get($name)
    {
        if (in_array($name, $this->routes, true)) {
            return new Helpers\Route($name, $this->secret_key);
        }
    }
}
