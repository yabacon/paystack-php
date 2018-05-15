<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Subaccount;

class SubaccountTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Subaccount();
        $this->assertEquals('/subaccount', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Subaccount();
        $this->assertEquals('/subaccount', $r->create()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subaccount', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subaccount/{id}', $r->fetch()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subaccount/{id}', $r->update()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Subaccount();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->create()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->fetch()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::PUT_METHOD, $r->update()[RouteInterface::METHOD_KEY]);
    }
}
