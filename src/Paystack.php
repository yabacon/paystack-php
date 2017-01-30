<?php

namespace Yabacon;

use \Yabacon\Paystack\Helpers\Router;
use \Yabacon\Paystack\Exception\ValidationException;

class Paystack
{
    public $secret_key;
    public $use_guzzle = false;

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

    public function __call($method, $args)
    {
        if ($singular_form = Router::singularFor($method)) {
            $this->handlePlural($singular_form, $method, $args);
        }
        $this->handleSingular($method, $args);
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
        trigger_error('Include "src/autoload.php" instead', E_USER_NOTICE);
        spl_autoload_register(
            function ($class_name) {
                $file = dirname(__FILE__) . DIRECTORY_SEPARATOR;
                $file .= str_replace([ 'Yabacon\\', '\\' ], ['', DIRECTORY_SEPARATOR ], $class_name) . '.php';
                include_once $file;
            }
        );
    }

    public function __get($name)
    {
        return new Router($name, $this);
    }
}
