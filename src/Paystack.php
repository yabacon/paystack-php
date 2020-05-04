<?php

namespace Yabacon;

use \Yabacon\Paystack\Helpers\Router;
use \Yabacon\Paystack\Contracts\RouteInterface;
use \Yabacon\Paystack\Exception\ValidationException;

class Paystack
{
    public $secret_key;
    public $use_guzzle = false;
    public $custom_routes = [];
    public static $fallback_to_file_get_contents = true;
    const VERSION="2.1.19";

    public function __construct($secret_key)
    {
        if (!is_string($secret_key) || !(substr($secret_key, 0, 3)==='sk_')) {
            throw new \InvalidArgumentException('A Valid Paystack Secret Key must start with \'sk_\'.');
        }
        $this->secret_key = $secret_key;
    }

    public function useGuzzle()
    {
        $this->use_guzzle = true;
    }

    public function useRoutes(array $routes)
    {
        foreach ($routes as $route => $class) {
            if (! is_string($route)) {
                throw new \InvalidArgumentException(
                    'Custom routes should map to a route class'
                );
            }

            if (in_array($route, Router::$ROUTES)) {
                throw new \InvalidArgumentException(
                    $route . ' is already an existing defined route'
                );
            }

            if (! in_array(RouteInterface::class, class_implements($class))) {
                throw new \InvalidArgumentException(
                    'Custom route class ' . $class . 'should implement ' . RouteInterface::class
                );
            }
        }
        
        $this->custom_routes = $routes;
    }

    public static function disableFileGetContentsFallback()
    {
        Paystack::$fallback_to_file_get_contents = false;
    }

    public static function enableFileGetContentsFallback()
    {
        Paystack::$fallback_to_file_get_contents = true;
    }

    public function __call($method, $args)
    {
        if ($singular_form = Router::singularFor($method)) {
            return $this->handlePlural($singular_form, $method, $args);
        }
        return $this->handleSingular($method, $args);
    }

    private function handlePlural($singular_form, $method, $args)
    {
        if ((count($args) === 1 && is_array($args[0]))||(count($args) === 0)) {
            return $this->{$singular_form}->__call('getList', $args);
        }
        throw new \InvalidArgumentException(
            'Route "' . $method . '" can only accept an optional array of filters and '
            .'paging arguments (perPage, page).'
        );
    }

    private function handleSingular($method, $args)
    {
        if (count($args) === 1) {
            $args = [[], [ Router::ID_KEY => $args[0] ] ];
            return $this->{$method}->__call('fetch', $args);
        }
        throw new \InvalidArgumentException(
            'Route "' . $method . '" can only accept an id or code.'
        );
    }

    /**
     * @deprecated
     */
    public static function registerAutoloader()
    {
        trigger_error('Include "src/autoload.php" instead', E_DEPRECATED | E_USER_NOTICE);
        require_once(__DIR__ . '/../src/autoload.php');
    }

    public function __get($name)
    {
        return new Router($name, $this);
    }
}
