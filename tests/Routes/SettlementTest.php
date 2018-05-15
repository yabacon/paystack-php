<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Settlement;

class SettlementTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Settlement();
        $this->assertEquals('/settlement', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Settlement();
        $this->assertEquals('/settlement', $r->getList()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Settlement();
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
    }
}
