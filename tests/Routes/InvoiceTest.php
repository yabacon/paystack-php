<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Invoice;

class InvoiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Invoice();
        $this->assertEquals('/paymentrequest', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Invoice();
        $this->assertEquals('/paymentrequest', $r->create()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/paymentrequest', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/paymentrequest/{invoice_id_or_code}', $r->fetch()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/paymentrequest/update/{invoice_id_or_code}', $r->update()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/paymentrequest/verify/{invoice_id_or_code}', $r->verify()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/paymentrequest/notify/{invoice_id_or_code}', $r->notify()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/paymentrequest/totals', $r->metrics()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals(
            '/paymentrequest/finalize/{invoice_id_or_code}',
            $r->finalize()[RouteInterface::ENDPOINT_KEY]
        );
        $this->assertEquals(
            '/paymentrequest/archive/{invoice_id_or_code}',
            $r->archive()[RouteInterface::ENDPOINT_KEY]
        );
    }

    public function testMethods()
    {
        $r = new Invoice();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->create()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->fetch()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::PUT_METHOD, $r->update()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->verify()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->notify()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->metrics()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->finalize()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->archive()[RouteInterface::METHOD_KEY]);
    }
}
