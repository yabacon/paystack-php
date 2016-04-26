<?php
namespace Yabacon\Paystack\Tests;

use Yabacon\Paystack;

class PaystackTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializeWithInvalidSecretKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $r = new Paystack('p');
    }
}
