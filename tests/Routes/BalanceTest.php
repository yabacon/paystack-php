<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Balance;

class BalanceTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Balance();
        $this->assertEquals('/balance', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Balance();
        $this->assertEquals('/balance', $r->getList()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Balance();
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
    }
}
