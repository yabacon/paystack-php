<?php

namespace Yabacon\Paystack\Tests\Exception;

use Yabacon\Paystack\Exception\PaystackException;

class PaystackExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $e = new PaystackException('message');
        $this->assertNotNull($e);
    }
}
