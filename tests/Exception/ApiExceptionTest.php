<?php

namespace Yabacon\Paystack\Tests\Exception;

use Yabacon\Paystack\Exception\ApiException;

class ApiExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $e = new ApiException('message', new \stdClass());
        $this->assertNotNull($e);
        $this->assertNotNull($e->getResponseObject());
    }
}
