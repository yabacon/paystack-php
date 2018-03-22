<?php
namespace Yabacon\Paystack\Tests\Helpers;

use Yabacon\Paystack\Helpers\Caller;
use Yabacon\Paystack;
use Yabacon\Paystack\Contracts\RouteInterface;

class CallerTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $p = new Paystack('sk_');
        $c = new Caller($p);
        $this->assertNotNull($c);
    }
}
