<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Transferrecipient;

class TransferrecipientTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Transferrecipient();
        $this->assertEquals('/transferrecipient', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Transferrecipient();
        $this->assertEquals('/transferrecipient', $r->create()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transferrecipient', $r->getList()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Transferrecipient();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->create()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
    }
}
