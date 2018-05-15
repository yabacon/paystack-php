<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Subscription;

class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Subscription();
        $this->assertEquals('/subscription', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Subscription();
        $this->assertEquals('/subscription', $r->create()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subscription', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subscription/{id}', $r->fetch()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subscription/enable', $r->enable()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/subscription/disable', $r->disable()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Subscription();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->create()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->fetch()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->enable()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->disable()[RouteInterface::METHOD_KEY]);
    }
}
