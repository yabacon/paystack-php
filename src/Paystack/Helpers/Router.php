<?php

namespace Yabacon\Paystack\Helpers;

use \Closure;
use \Yabacon\Paystack\Contracts\RouteInterface;

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
        if (($method === RouteInterface::POST_METHOD)
            || ($method === RouteInterface::PUT_METHOD)
        ) {
            $headers["Content-Type"] = "application/json";
            $body = json_encode($payload);
        } elseif ($method === RouteInterface::GET_METHOD) {
            $endpoint = $endpoint . '?' . http_build_query($payload);
        }
        // Use Guzzle if found, else use Curl
        if ($this->use_guzzle && class_exists('\\GuzzleHttp\\Client') && class_exists('\\GuzzleHttp\\Psr7\\Request')) {
            $request = new \GuzzleHttp\Psr7\Request(strtoupper($method), $endpoint, $headers, $body);
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->send($request);
            } catch (\Exception $e) {
                if ($e->hasResponse()) {
                    $response = $e->getResponse();
                } else {
                    throw $e;
                }
            }
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

            // Make sure CURL_SSLVERSION_TLSv1_2 is defined as 6
            // Curl must be able to use TLSv1.2 to connect
            // to Paystack servers
            
            if (!defined('CURL_SSLVERSION_TLSV1_2')) {
                define('CURL_SSLVERSION_TLSV1_2', 6);
            }
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSV1_2);

            $response = \curl_exec($ch);
            
            if (\curl_errno($ch)) {   // should be 0
                // curl ended with an error
                $cerr = \curl_error($ch);
                \curl_close($ch);
                throw new \Exception("Curl failed with response: '" . $cerr . "'.");
            }

            // Then, after your \curl_exec call:
            $resp = json_decode($response);
            //close connection
            \curl_close($ch);

            if (!$resp->status) {
                throw new \Exception("Paystack Request failed with response: '" . $resp->message . "'.");
            }

            return $resp;
        }

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
            throw new \Exception('Function "' . $method . '" does not exist for "' . $this->route . '".');
        }
    }

    /**
 * A magic resource object that can make method calls to API
 *
 * @param $route
 * @param $paystackObj - A Yabacon\Paystack Object
 */
    public function __construct($route, $paystackObj)
    {
        $this->route = strtolower($route);
        $this->route_class = 'Yabacon\\Paystack\\Routes\\' . ucwords($route);
        $this->secret_key = $paystackObj->secret_key;
        $this->use_guzzle = $paystackObj->use_guzzle;

        $mets = get_class_methods($this->route_class);
        if (empty($mets)) {
            throw new \InvalidArgumentException('Class "' . $this->route . '" does not exist.');
        }
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
