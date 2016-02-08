<?php

namespace Eidetic\Paystack\Helpers;

use \Closure;
use \Eidetic\Paystack\Contracts\RouteInterface;

class Router
{

    private $route;
    private $route_class;
    private $secret_key;

    const ID_KEY = 'id';
    const PAYSTACK_API_ROOT = 'https://api.paystack.co';

    const HEADER_KEY = 'header';
    const BODY_KEY = 'body';
    
    private function moveArgsToSentargs($interface, &$payload, &$sentargs)
    {
        // check if interface supports args
        if (array_key_exists(RouteInterface::ARGS_KEY, $interface)) {
            // to allow args to be specified in the payload, filter them out and put them in sentargs
            $sentargs = (!$sentargs) ? [] : $sentargs; // Make sure $sentargs is not null
            $args = $interface[RouteInterface::ARGS_KEY];
            while (list($key, $value) = each($payload)) {
                // check that a value was specified
                // with a key that was expected as an arg
                if (in_array($key, $args)) {
                    $sentargs[$key] = $value;
                    unset($payload[$key]);
                }
            }
        }
    }
    
    private function putArgsIntoEndpoint(&$endpoint, $sentargs)
    {
        // substitute sentargs in endpoint
        while (list($key, $value) = each($sentargs)) {
            $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
        }
    }

    private function callViaCurl($interface, $payload = [], $sentargs = [])
    {
        $endpoint = $interface[RouteInterface::ENDPOINT_KEY];
        $method = $interface[RouteInterface::METHOD_KEY];
        
        $this->moveArgsToSentargs($interface, $payload, $sentargs);
        $this->putArgsIntoEndpoint($endpoint, $sentargs);
        
        //open connection
        $ch = \curl_init();

        $headers = ["Authorization: Bearer " . $this->secret_key];
        if ($method === RouteInterface::POST_METHOD || $method === RouteInterface::PUT_METHOD) {
            //set the url
            \curl_setopt($ch, \CURLOPT_URL, Router::PAYSTACK_API_ROOT . $endpoint);

            $headers[] = "Content-Type: application/json";
            ($method === RouteInterface::POST_METHOD) && \curl_setopt($ch, \CURLOPT_POST, true);
            ($method === RouteInterface::PUT_METHOD) && \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            ;
//             echo Router::PAYSTACK_API_ROOT . $endpoint . '?' . http_build_query($payload);
//             die();
            \curl_setopt($ch, \CURLOPT_POSTFIELDS, json_encode($payload));
        } else {
            //set the url
            \curl_setopt($ch, \CURLOPT_URL, Router::PAYSTACK_API_ROOT . $endpoint . '?' . http_build_query($payload));
//             echo Router::PAYSTACK_API_ROOT . $endpoint . '?' . http_build_query($payload);
//             die();
        }
        //set the headers
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        // \curl_setopt($ch, \CURLOPT_VERBOSE, 1);
        \curl_setopt($ch, \CURLOPT_HEADER, 1);

        $response = \curl_exec($ch);

        // Then, after your \curl_exec call:
        $header_size = \curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        //close connection
        \curl_close($ch);

        return [Router::HEADER_KEY => $header, Router::BODY_KEY => $body];
    }

    public function __call($method, $sentargs)
    {
        $method = ($method === 'list' ? 'getList' : $method);
        if (array_key_exists($method, $this->methods) && is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $sentargs);
        } else {
            // User attempted to call a function that does not exist
            throw new \Exception('Function "'.$method.'" does not exist for "'.$this->route."'.");
        }
    }

    public function __construct($route, $secret_key)
    {
        $this->route = strtolower($route);
        $this->route_class = 'Eidetic\\Paystack\\Routes\\' . ucwords($route);
        $this->secret_key = $secret_key;
        // change method named list to getList
        $mets = get_class_methods($this->route_class);
        // add methods to this object for each method, except the root
        foreach ($mets as $mtd) {
            if ($mtd === 'root') {
                // skip root method
                continue;
            }
            $mtdFunc = function (array $params = [], array $sentargs = []) use ($mtd) {
                //                print_r($params);
                //                print_r($sentargs);
                $interface = call_user_func($this->route_class . '::' . $mtd);
                // TODO: validate params and sentargs against definitions
                return $this->callViaCurl(
                    $interface,
                    $params,
                    $sentargs
                );
            };
            $this->methods[$mtd] = \Closure::bind($mtdFunc, $this, get_class());
        }
    }
}
