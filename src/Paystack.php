<?php

namespace YabaCon;

use \YabaCon\Paystack\Helpers\Router;

/**
 *
 */
class Paystack
{

    private $secret_key;
    private $routes = ['plan',
        'transaction',
        'customer'
    ];

    /**
     * Creates a new Paystack object
     * @param string $secret_key - Secret key for your account with Paystack
     */
    public function __construct($secret_key)
    {
        $this->secret_key = $secret_key;
    }

    /**
     * __call
     * Insert description here
     *
     * @param $method
     * @param $args
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public function __call($method, $args)
    {
//attempt to call getOne when the route is called directly
// translates to /{root}/{get}/{id}
        if (in_array(
            $method,
            $this->routes,
            true
        )) {
            $route = new Router(
                $method,
                $this->secret_key
            );
// Router::put_non_array_values_into_array($args);

            if (count($args) === 1 && is_integer($args[0])) {
// no params, just one arg... the id
                $args = [[],
                    [ Router::ID_KEY => $args[0] ] ];
                return $route->__call(
                    'getOne',
                    $args
                );
            } elseif (count($args) === 2 && is_integer($args[0]) && is_array($args[1])) {
// there are params, and just one arg... the id
                $args = [$args[1],
                    [ Router::ID_KEY => $args[0] ] ];
                return $route->__call(
                    'getOne',
                    $args
                );
            }
        }
// Should never get here
        throw new \Exception(
            'Route "' . $method . '" could not be called. Be sure to send an integer id.'
        );
    }

    /**
     * Call this function to register the custom auto loader for Paystack.
     * Required only if not using composer.
     */
    public static function registerAutoloader()
    {
        spl_autoload_register(
            /**
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
                $file .= str_replace(
                    [ 'YabaCon\\',
                    '\\' ],
                    [
                    '',
                    DIRECTORY_SEPARATOR ],
                    $class_name
                ) . '.php';
            // die();

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
        if (in_array(
            $name,
            $this->routes,
            true
        )) {
            return new Router(
                $name,
                $this->secret_key
            );
        }
    }
}
