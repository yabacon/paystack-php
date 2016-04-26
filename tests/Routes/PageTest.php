<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Routes\Page;
use Yabacon\Paystack\Contracts\RouteInterface;

class PageTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Page();
        $this->assertEquals('/page', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Page();
        $this->assertEquals('/page', $r->create()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/page', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/page/{id}', $r->fetch()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/page/{id}', $r->update()[RouteInterface::ENDPOINT_KEY]);
    }
    
    public function testMethods()
    {
        $r = new Page();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->create()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->fetch()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::PUT_METHOD, $r->update()[RouteInterface::METHOD_KEY]);
    }
}
