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
    
    private function attemptGuzzle($method, $endpoint, $headers, $body)
    {
        if ($this->use_guzzle &&
            class_exists('\\GuzzleHttp\\Exception\\BadResponseException') &&
            class_exists('\\GuzzleHttp\\Exception\\ClientException') &&
            class_exists('\\GuzzleHttp\\Exception\\ConnectException') &&
            class_exists('\\GuzzleHttp\\Exception\\RequestException') &&
            class_exists('\\GuzzleHttp\\Exception\\ServerException') &&
            class_exists('\\GuzzleHttp\\Exception\\TooManyRedirectsException') &&
            class_exists('\\GuzzleHttp\\Client') &&
            class_exists('\\GuzzleHttp\\Psr7\\Request')) {
            $request = new \GuzzleHttp\Psr7\Request(strtoupper($method), $endpoint, $headers, $body);
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->send($request);
            } catch (\Exception $e) {
                if (($e instanceof \GuzzleHttp\Exception\BadResponseException
                    || $e instanceof \GuzzleHttp\Exception\ClientException
                    || $e instanceof \GuzzleHttp\Exception\ConnectException
                    || $e instanceof \GuzzleHttp\Exception\RequestException
                    || $e instanceof \GuzzleHttp\Exception\ServerException
                    || $e instanceof \GuzzleHttp\Exception\TooManyRedirectsException
                    ) && $e->hasResponse()) {
                    $response = $e->getResponse();
                } else {
                    throw $e;
                }
            }
            return $response;
        } else {
            return false;
        }
    }

    /**
 * callEndpoint
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
    private function callEndpoint($interface, $payload = [ ], $sentargs = [ ])
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
        $guzzleResponse = $this->attemptGuzzle($method, $endpoint, $headers, $body);
        if ($guzzleResponse !== false) {
            return $guzzleResponse;
        }
        
        return $this->attemptCurl($method, $endpoint, $headers, $body);
    }

    private function attemptCurl($method, $endpoint, $headers, $body)
    {
        //open connection
        $ch = \curl_init();
        \curl_setopt($ch, \CURLOPT_URL, $endpoint);

        if ($method === RouteInterface::POST_METHOD || $method === RouteInterface::PUT_METHOD) {
            ($method === RouteInterface:: POST_METHOD) && \curl_setopt($ch, \CURLOPT_POST, true);
            ($method === RouteInterface ::PUT_METHOD) && \curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, "PUT");

            \curl_setopt($ch, \CURLOPT_POSTFIELDS, $body);
        }
        //flatten the headers
        $flattened_headers = [];
        while (list($key, $value) = each($headers)) {
            $flattened_headers[] = $key . ": " . $value;
        }
        \curl_setopt($ch, \CURLOPT_HTTPHEADER, $flattened_headers);
        \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, \CURLOPT_SSLVERSION, 6);

        $response = \curl_exec($ch);
        
        if (\curl_errno($ch)) {   // should be 0
            // curl ended with an error
            $cerr = \curl_error($ch);
            \curl_close($ch);
            throw new \Exception("Curl failed with response: '" . $cerr . "'.");
        }

        // Decode JSON from Paystack:
        $resp = \json_decode($response);
        \curl_close($ch);

        if (json_last_error() !== JSON_ERROR_NONE || !$resp->status) {
            throw new \Exception("Paystack Request failed with response: '" .
            ((json_last_error() === JSON_ERROR_NONE) ? $resp->message : $response) . "'.");
        }

        return $resp;
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
                return $this->callEndpoint($interface, $params, $sentargs);
            };
            $this->methods[$mtd] = \Closure::bind($mtdFunc, $this, get_class());
        }
    }
}
