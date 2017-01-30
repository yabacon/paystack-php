<?php

namespace Yabacon\Paystack\Http;

use \Yabacon\Paystack\Exception\ApiException;

class Response
{
    public $okay;
    public $body;
    public $forApi;
    public $messages = [];

    private function parsePaystackResponse()
    {
        $resp = \json_decode($this->body);

        if (json_last_error() !== JSON_ERROR_NONE || !$resp->status) {
            throw new ApiException(
                "Paystack Request failed with response: '" .
                ((json_last_error() === JSON_ERROR_NONE) ? $resp->message : $this->body) . "'."
            );
        }

        return $resp;
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
            throw new Exception($this->implodedMessages());
        }
        if ($this->okay) {
            error_log($this->implodedMessages());
            return $this->body;
        }
        return false;
    }
}
