<?php
namespace Yabacon\Paystack\Contracts;

interface RouteInterface
{

    const METHOD_KEY = 'method';
    const ENDPOINT_KEY = 'endpoint';
    const PARAMS_KEY = 'params';
    const ARGS_KEY = 'args';
    const REQUIRED_KEY = 'required';
    const POST_METHOD = 'post';
    const PUT_METHOD = 'put';
    const GET_METHOD = 'get';

    public static function root();
}
