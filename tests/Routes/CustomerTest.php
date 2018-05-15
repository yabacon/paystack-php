<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Customer;

class CustomerTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Customer();
        $this->assertEquals('/customer', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Customer();
        $this->assertEquals('/customer', $r->create()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/customer', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/customer/{id}', $r->fetch()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/customer/{id}', $r->update()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Customer();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->create()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->fetch()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::PUT_METHOD, $r->update()[RouteInterface::METHOD_KEY]);
    }
}
