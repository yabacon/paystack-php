<?php

namespace Yabacon;

use \Yabacon\Paystack\Helpers\Router;

class Paystack
{

    public $secret_key;
    private $routes;
    public $use_guzzle = false;

    public function __construct($secret_key)
    {
        if (!is_string($secret_key) || !(substr($secret_key, 0, 3)==='sk_')) {
            // Should never get here
            throw new \InvalidArgumentException('A Valid Paystack Secret Key must start with \'sk_\'.');
        }
         $this->secret_key = $secret_key;
         $this->routes = $this->definedRoutes();
    }

    public function useGuzzle()
    {
         $this->use_guzzle = true;
    }

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
     * Magic Method for fetch on routes
     *
     * @param $method - a string whose title case is a class in the
     *                  Yabacon\Paystack\Routes namespace implementing
     *                  Yabacon\Paystack\Contracts\RouteInterface
     * @param $args - arguments sent to the magic method
     *
     * @return the result of calling /{route}/get on the api
     *
     * @access public
     * @see    Yabacon\Paystack\Routes\Router
     * @since  1.0
     */
    public function __call($method, $args)
    {
        /*
        attempt to call fetch when the route is called directly
        translates to /{root}/{get}/{id}
        */
        if (in_array($method, $this->routes, true) && count($args) === 1) {
            $route = new Router($method, $this);
            // no params, just one arg... the id
            $args = [[], [ Router::ID_KEY => $args[0] ] ];
            return $route->__call('fetch', $args);
        }

        // Not found is it plural?
        $is_plural = strripos($method, 's')===(strlen($method)-1);
        $singular_form = substr($method, 0, strlen($method)-1);

        if ($is_plural && in_array($singular_form, $this->routes, true)) {
            $route = new Router($singular_form, $this);
            if ((count($args) === 1 && is_array($args[0]))||(count($args) === 0)) {
                return $route->__call('getList', $args);
            }
        }

        // Should never get here
        throw new \InvalidArgumentException(
            'Route "' . $method . '" can only accept '.
            ($is_plural ?
                        'an optional array of paging arguments (perPage, page)'
                        : 'an id or code') . '.'
        );
    }

    /**
     * @deprecated
     */
    public static function registerAutoloader()
    {
        trigger_error('Include "src/autoload.php" instead', E_USER_NOTICE);
        spl_autoload_register(
            function ($class_name) {
                $file = dirname(__FILE__) . DIRECTORY_SEPARATOR;
                $file .= str_replace([ 'Yabacon\\', '\\' ], ['', DIRECTORY_SEPARATOR ], $class_name) . '.php';
                include_once $file;
            }
        );
    }

    /**
     * __get
     * Magic method to create a new Router
     * which performs all actions required
     */
    public function __get($name)
    {
        if (in_array($name, $this->routes, true)) {
            return new Router($name, $this);
        }
    }
}
