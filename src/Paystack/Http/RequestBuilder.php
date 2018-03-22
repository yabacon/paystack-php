<?php

namespace Yabacon\Paystack\Http;

use \Yabacon\Paystack\Contracts\RouteInterface;
use \Yabacon\Paystack\Helpers\Router;
use \Yabacon\Paystack;

class RequestBuilder
{
    protected $paystackObj;
    protected $interface;
    protected $request;

    public $payload = [ ];
    public $sentargs = [ ];

    public function __construct($paystackObj, $interface, array $payload = [ ], array $sentargs = [ ])
    {
        $this->request = new Request($paystackObj);
        $this->paystackObj = $paystackObj;
        $this->interface = $interface;
        $this->payload = $payload;
        $this->sentargs = $sentargs;
    }

    public function build()
    {
        $this->request->headers["Authorization"] = "Bearer " . $this->paystackObj->secret_key;
        $this->request->headers["User-Agent"] = "Paystack/v1 PhpBindings/" . Paystack::VERSION;
        $this->request->endpoint = Router::PAYSTACK_API_ROOT . $this->interface[RouteInterface::ENDPOINT_KEY];
        $this->request->method = $this->interface[RouteInterface::METHOD_KEY];
        $this->moveArgsToSentargs();
        $this->putArgsIntoEndpoint($this->request->endpoint);
        $this->packagePayload();
        return $this->request;
    }

    public function packagePayload()
    {
        if (is_array($this->payload) && count($this->payload)) {
            if ($this->request->method === RouteInterface::GET_METHOD) {
                $this->request->endpoint = $this->request->endpoint . '?' . http_build_query($this->payload);
            } else {
                $this->request->body = json_encode($this->payload);
            }
        }
    }

    public function putArgsIntoEndpoint(&$endpoint)
    {
        foreach ($this->sentargs as $key => $value) {
            $endpoint = str_replace('{' . $key . '}', $value, $endpoint);
        }
    }

    public function moveArgsToSentargs()
    {
        if (!array_key_exists(RouteInterface::ARGS_KEY, $this->interface)) {
            return;
        }
        $args = $this->interface[RouteInterface::ARGS_KEY];
        foreach ($this->payload as $key => $value) {
            if (in_array($key, $args)) {
                $this->sentargs[$key] = $value;
                unset($this->payload[$key]);
            }
        }
    }
}
