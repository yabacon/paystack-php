<?php

namespace League\Paystack\Helpers;

use \Closure;
use \League\Paystack\Contracts\RouteInterface;

class Route
{

    private $route_class;
    private $secret_key;

    const ID_KEY = 'id';
    const PAYSTACK_API_ROOT = 'https://api.paystack.co';

    const HEADER_KEY = 'header';
    const BODY_KEY = 'body';

    const POST_METHOD = 'post';
    const PUT_METHOD = 'put';
    const GET_METHOD = 'get';

    // public static function put_non_array_values_into_array(&$arr,$key=Route::ID_KEY){
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

    private function callViaCurl($endpoint, $method, $payload = [], $args = [])
    {
        //substitute args in endpoint
        while (list($key, $value) = each($args)) {
            $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
        }

        //open connection
        $ch = \curl_init();

        //set the url, number of POST vars, POST data
        \curl_setopt($ch, \CURLOPT_URL, Route::PAYSTACK_API_ROOT . $endpoint);

        $headers = ["Authorization: Bearer " . $this->secret_key];
        if ($method === Route::POST_METHOD || $method === Route::PUT_METHOD) {
            $headers[] = "Content-Type: application/json";
            //set number of POST vars, POST data
            \curl_setopt($ch, \CURLOPT_POST, true);
            \curl_setopt($ch, \CURLOPT_POSTFIELDS, json_encode($payload));
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

        return [Route::HEADER_KEY => $header, Route::BODY_KEY => $body];
    }

    function __call($method, $args)
    {
        $method = ($method === 'list' ? 'getList' : $method);
        if (is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $args);
        }
    }

    function __construct($route_class, $secret_key)
    {
        $this->route_class = 'League\\Paystack\\Routes\\' . ucwords($route_class);
        $this->secret_key = $secret_key;
        // change method named list to getList
        $mets = get_class_methods($this->route_class);
        // add methods to this object for each, except the root
        foreach ($mets as $mtd) {
            if ($mtd === 'root') {
                // skip root method
                continue;
            }
            $mtdFunc = function (array $params = [], array $args = []) use ($mtd) {
                //                print_r($params);
                //                print_r($args);
                $interface = call_user_func($this->route_class . '::' . $mtd);
                // TODO: validate params and args against definitions
                return $this->callViaCurl(
                    $interface[RouteInterface::ENDPOINT_KEY],
                    $interface[RouteInterface::METHOD_KEY],
                    $params,
                    $args
                );
            };
            $this->methods[$mtd] = \Closure::bind($mtdFunc, $this, get_class());
        }
    }
}
