<?php

namespace YabaCon;

use \YabaCon\Paystack\Helpers\Router;

/**
 *
 */
class Paystack
{

    public $secret_key;
    private $routes;
    public $use_guzzle = false;

 /**
 * Creates a new Paystack object
 * @param string $secret_key - Secret key for your account with Paystack
 */
    public function __construct($secret_key)
    {
        
        if (!is_string($secret_key) || !(substr($secret_key, 0, 3)==='sk_')) {
        // Should never get here
            throw new \InvalidArgumentException('A Valid Paystack Secret Key must start with \'sk_\'.');
   
        }
         $this->secret_key = $secret_key;
         $this->routes = $this->definedRoutes();
    }

 /**
 * Router uses Guzzle for calls if GuzzleHttp is found
 */
    public function useGuzzle()
    {
         $this->use_guzzle = true;
    }

 /**
 * definedRoutes
 * gets routes defined in the YabaCon\Paystack\Routes namespace
 * since we are using PSR-4. It is safe to assume that all php
 * files in the folder are names of the classes.
 *
 * @return the list of defined Routes
 *
 * @access private
 * @see YabaCon\Paystack\Routes\Router
 * @since 1.0
 */
    private function definedRoutes()
    {
        $routes = [];
        $files = scandir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Paystack'.DIRECTORY_SEPARATOR.'Routes');
        foreach ($files as $file) {
            if ('php'===pathinfo($file, PATHINFO_EXTENSION)) {
                $routes[] = strtolower(substr($file, 0, strrpos($file, ".")));
            }
        }
        return $routes;
    }
 /**
 * __call
 * Magic Method for getOne on routes
 *
 * @param $method - a string whose title case is a class in the
 *                  YabaCon\Paystack\Routes namespace implementing
 *                  YabaCon\Paystack\Contracts\RouteInterface
 * @param $args - arguments sent to the magic method
 *
 * @return the result of calling /{route}/get on the api
 *
 * @access public
 * @see YabaCon\Paystack\Routes\Router
 * @since 1.0
 */
    public function __call($method, $args)
    {
   /*
   attempt to call getOne when the route is called directly
  translates to /{root}/{get}/{id}
  */
        if (in_array($method, $this->routes, true)) {
            $route = new Router($method, $this);
    
            if (count($args) === 1 && is_integer($args[0])) {
           // no params, just one arg... the id
                $args = [[], [ Router::ID_KEY => $args[0] ] ];
                return $route->__call('getOne', $args);
            } elseif (count($args) === 2 && is_integer($args[0]) && is_array($args[1])) {
           // there are params, and just one arg... the id
                $args = [$args[1], [ Router::ID_KEY => $args[0] ] ];
                return $route->__call('getOne', $args);
            }
        }
   // Should never get here
        throw new \InvalidArgumentException('Route "' .
        $method .
         '" only accepts an integer id and an optional array of paging arguments.');
    }

 /**
 * Call this function to register the custom auto loader for Paystack. * Required only if not using composer. */
    public static function registerAutoloader()
    {
        spl_autoload_register( /**
            * $class_name
            * Insert description here
            *
            *
            * @return
            *
            * @access
            * @static
            * @see
            * @since
            */
            function ($class_name) {
                $file = dirname(__FILE__) . DIRECTORY_SEPARATOR;
                $file .= str_replace([ 'YabaCon\\', '\\' ], ['', DIRECTORY_SEPARATOR ], $class_name) . '.php';

                include_once $file;
            }
        );
    }

 /**
 * __get
 * Insert description here
 *
 * @param $name
 *
 * @return
 *
 * @access
 * @static
 * @see
 * @since
 */
    public function __get($name)
    {
        if (in_array($name, $this->routes, true)) {
            return new Router($name, $this);
        }
    }
}
