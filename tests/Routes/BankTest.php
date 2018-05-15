<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Bank;

class BankTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Bank();
        $this->assertEquals('/bank', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Bank();
        $this->assertEquals('/bank', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/bank/resolve_bvn/{bvn}', $r->resolveBvn()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/bank/resolve', $r->resolve()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Bank();
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->resolveBvn()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->resolve()[RouteInterface::METHOD_KEY]);
    }
}
