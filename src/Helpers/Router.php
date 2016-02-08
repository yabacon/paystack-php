<?php

namespace Eidetic\Paystack\Helpers;

use \Closure;
use \Eidetic\Paystack\Contracts\RouteInterface;

class Router
{

    private $route_class;
    private $secret_key;

    const ID_KEY = 'id';
    const PAYSTACK_API_ROOT = 'https://api.paystack.co';

    const HEADER_KEY = 'header';
    const BODY_KEY = 'body';

    // public static function put_non_array_values_into_array(&$arr,$key=Router::ID_KEY){
    //     $argscount = count($arr);
    //     for($i=0;$i<$argscount;$i++){
    //
    //     if(!is_array($arr[$i])){
    //       $arr[$i] = [$key=>$arr[$i]];
    //     }
    //
    //
    //     }
    //
    //     }

    private function callViaCurl($interface, $payload = [], $sentargs = [])
    {
        $endpoint = $interface[RouteInterface::ENDPOINT_KEY];
        $method = $interface[RouteInterface::METHOD_KEY];
        
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
        
        // substitute sentargs in endpoint
        while (list($key, $value) = each($sentargs)) {
            $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
        }

        //open connection
        $ch = \curl_init();

        $headers = ["Authorization: Bearer " . $this->secret_key];
        if ($method === RouteInterface::POST_METHOD || $method === RouteInterface::PUT_METHOD) {
            //set the url
            \curl_setopt($ch, \CURLOPT_URL, Router::PAYSTACK_API_ROOT . $endpoint);

            $headers[] = "Content-Type: application/json";
            //set number of POST vars, POST data
            \curl_setopt($ch, \CURLOPT_POST, true);
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
        if (is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $sentargs);
        }
    }

    public function __construct($route_class, $secret_key)
    {
        $this->route_class = 'Eidetic\\Paystack\\Routes\\' . ucwords($route_class);
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
