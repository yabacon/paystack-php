<?php

/**
 * A Route
 */

namespace League\Paystack\Contracts;

interface RouteInterface
{

    const METHOD_KEY = 'method';
    const ENDPOINT_KEY = 'endpoint';
    const PARAMS_KEY = 'params';
    const ARGS_KEY = 'args';

    /**
     */
    public static function root();
}
