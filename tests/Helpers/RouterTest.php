<?php
namespace Yabacon\Paystack\Tests\Helpers;

use Yabacon\Paystack\Helpers\Router;
use Yabacon\Paystack;
use Yabacon\Paystack\Contracts\RouteInterface;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $p = new Paystack('sk_');
        $this->expectException(\InvalidArgumentException::class);
        $r = new Router('nonexistent', $p);
    }
}
