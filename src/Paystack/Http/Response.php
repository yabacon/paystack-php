<?php

namespace Yabacon\Paystack\Http;

use \Yabacon\Paystack\Exception\ApiException;

class Response
{
    public $okay;
    public $body;
    public $forApi;
    public $messages = [];

    private $requestObject;

    public function setRequestObject($requestObject)
    {
        $this->requestObject = $requestObject;
    }

    public function getRequestObject()
    {
        return $this->requestObject;
    }

    private function parsePaystackResponse()
    {
        $resp = \json_decode($this->body);

        if ($resp === null || !property_exists($resp, 'status') || !$resp->status) {
            throw new ApiException(
                "Paystack Request failed with response: '" .
                $this->messageFromApiJson($resp)."'",
                $resp,
                $this->requestObject
            );
        }

        return $resp;
    }

    private function messageFromApiJson($resp)
    {
        $message = $this->body;
        if ($resp !== null) {
            if (property_exists($resp, 'message')) {
                $message = $resp->message;
            }
            if (property_exists($resp, 'errors') && ($resp->errors instanceof \stdClass)) {
                $message .= "\nErrors:\n";
                foreach ($resp->errors as $field => $errors) {
                    $message .= "\t" . $field . ":\n";
                    foreach ($errors as $_unused => $error) {
                        $message .= "\t\t" . $error->rule . ": ";
                        $message .= $error->message . "\n";
                    }
                }
            }
        }
        return $message;
    }

    private function implodedMessages()
    {
        return implode("\n\n", $this->messages);
    }

    public function wrapUp()
    {
        if ($this->okay && $this->forApi) {
            return $this->parsePaystackResponse();
        }
        if (!$this->okay && $this->forApi) {
            throw new \Exception($this->implodedMessages());
        }
        if ($this->okay) {
            return $this->body;
        }
        error_log($this->implodedMessages());
        return false;
    }
}
