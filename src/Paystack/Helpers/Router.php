<?php

namespace YabaCon\Paystack\Helpers;

use \Closure;
use \YabaCon\Paystack\Contracts\RouteInterface;

/**
 * Router
 * Insert description here
 *
 * @category
 * @package
 * @author
 * @copyright
 * @license
 * @version
 * @link
 * @see
 * @since
 */
class Router
{

    private $route;
    private $route_class;
    private $secret_key;
    private $methods;
    private $use_guzzle=false;

    const ID_KEY = 'id';
    const PAYSTACK_API_ROOT = 'https://api.paystack.co';
    const HEADER_KEY = 'header';
    const HTTP_CODE_KEY = 'httpcode';
    const BODY_KEY = 'body';

 /**
 * moveArgsToSentargs
 * Insert description here
 *
 * @param $interface
 * @param $payload
 * @param $sentargs
 *
 * @return
 *
 * @access
 * @static
 * @see
 * @since
 */
    private function moveArgsToSentargs(
        $interface,
        &$payload,
        &$sentargs
    ) {



    // check if interface supports args
        if (array_key_exists(RouteInterface:: ARGS_KEY, $interface)) {
        // to allow args to be specified in the payload, filter them out and put them in sentargs
            $sentargs = (!$sentargs) ? [ ] : $sentargs; // Make sure $sentargs is not null
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

 /**
 * putArgsIntoEndpoint
 * Insert description here
 *
 * @param $endpoint
 * @param $sentargs
 *
 * @return
 *
 * @access
 * @static
 * @see
 * @since
 */
    private function putArgsIntoEndpoint(&$endpoint, $sentargs)
    {
    // substitute sentargs in endpoint
        while (list($key, $value) = each($sentargs)) {
            $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
        }
    }

 /**
 * callViaCurl
 * Insert description here
 *
 * @param $interface
 * @param $payload
 * @param $sentargs
 *
 * @return
 *
 * @access
 * @static
 * @see
 * @since
 */
    private function callViaCurl($interface, $payload = [ ], $sentargs = [ ])
    {
 

        $endpoint = Router::PAYSTACK_API_ROOT . $interface[RouteInterface::ENDPOINT_KEY];
        $method = $interface[RouteInterface::METHOD_KEY];

        $this->moveArgsToSentargs($interface, $payload, $sentargs);
        $this->putArgsIntoEndpoint($endpoint, $sentargs);
 
        $headers = ["Authorization"=>"Bearer " . $this->secret_key ];
        $body = '';
        if (($method === RouteInterface::POST_METHOD)||
        ($method === RouteInterface::PUT_METHOD)) {
            $headers["Content-Type"] = "application/json";
            $body = json_encode($payload);
        } elseif ($method === RouteInterface::GET_METHOD) {
            $endpoint = $endpoint . '?' . http_build_query($payload);
        }
    // Use Guzzle if found, else use Curl
        if ($this->use_guzzle && class_exists('\\GuzzleHttp\\Client') && class_exists('\\GuzzleHttp\\Psr7\\Request')) {
            $request = new \GuzzleHttp\Psr7\Request(strtoupper($method), $endpoint, $headers, $body);
            $client = new \GuzzleHttp\Client(['http_errors' => false]);
            $response = $client->send($request);
            return $response;
            
        } else {
        //open connection
        
            $ch = \curl_init();
        // set url
            \curl_setopt($ch, \CURLOPT_URL, $endpoint);
 
            if ($method === RouteInterface::POST_METHOD || $method === RouteInterface::PUT_METHOD) {
                ($method === RouteInterface:: POST_METHOD) && \curl_setopt($ch, \CURLOPT_POST, true);
                ($method === RouteInterface ::PUT_METHOD) && \curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

                \curl_setopt($ch, \CURLOPT_POSTFIELDS, $body);
            }
        //flatten the headers
            $flattened_headers = [];
            while (list($key, $value) = each($headers)) {
                $flattened_headers[] = $key . ": " . $value;
            }
            \curl_setopt($ch, \CURLOPT_HTTPHEADER, $flattened_headers);
            \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
            \curl_setopt($ch, \CURLOPT_HEADER, 1);

            $response = \curl_exec($ch);
            
            if (\curl_errno($ch)) {   // should be 0
            // curl ended with an error
                \curl_close($ch);
                return [[],[],0];
            }

            $code = \curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Then, after your \curl_exec call:
            $header_size = \curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $header = $this->headersFromLines(explode("\n", trim($header)));
            $body = substr($response, $header_size);
            $body = json_decode($body, true);
            

        //close connection
            \curl_close($ch);

            return [
            0 => $header, 1 => $body, 2=> $code,
            Router::HEADER_KEY => $header, Router::BODY_KEY => $body,
            Router::HTTP_CODE_KEY=>$code];
        }

    }
    
    private function headersFromLines($lines)
    {
        $headers = [];
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            $headers[trim($parts[0])][] = isset($parts[1])
            ? trim($parts[1])
            : null;
        }
        return $headers;
    }

 /**
 * __call
 * Insert description here
 *
 * @param $methd
 * @param $sentargs
 *
 * @return
 *
 * @access
 * @static
 * @see
 * @since
 */
    public function __call($methd, $sentargs)
    {
        $method = ($methd === 'list' ? 'getList' : $methd );
        if (array_key_exists($method, $this->methods) && is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $sentargs);
        } else {
        // User attempted to call a function that does not exist
            throw new \Exception('Function "' . $method . '" does not exist for "' . $this->route . "'.");
        }
    }

 /**
 * A magic resource object that can make method calls to API
 *
 * @param $route
 * @param $paystackObj - A YabaCon\Paystack Object
 */
    public function __construct($route, $paystackObj)
    {
        $this->route = strtolower($route);
        $this->route_class = 'YabaCon\\Paystack\\Routes\\' . ucwords($route);
        $this->secret_key = $paystackObj->secret_key;
        $this->use_guzzle = $paystackObj->use_guzzle;

        $mets = get_class_methods($this->route_class);
    // add methods to this object per method, except root
        foreach ($mets as $mtd) {
            if ($mtd === 'root') {
            // skip root method
                continue;
            }
        /**
 * array
 * Insert description here
 *
 * @param $params
 * @param array
 * @param $sentargs
 *
 * @return
 *
 * @access
 * @static
 * @see
 * @since
 */
            $mtdFunc = function (
                array $params = [ ],
                array $sentargs = [ ]
            ) use ($mtd) {
                $interface = call_user_func($this->route_class . '::' . $mtd);
            // TODO: validate params and sentargs against definitions
                return $this->callViaCurl($interface, $params, $sentargs);
            };
            $this->methods[$mtd] = \Closure::bind($mtdFunc, $this, get_class());
        }
    }
}
