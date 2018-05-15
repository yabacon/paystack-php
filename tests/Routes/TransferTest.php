<?php
namespace Yabacon\Paystack\Tests\Routes;

use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Transfer;

class TransferTest extends \PHPUnit_Framework_TestCase
{
    public function testRoot()
    {
        $r = new Transfer();
        $this->assertEquals('/transfer', $r->root());
    }

    public function testEndpoints()
    {
        $r = new Transfer();
        $this->assertEquals('/transfer', $r->initiate()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer', $r->getList()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer/{id}', $r->fetch()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer/finalize_transfer', $r->finalizeTransfer()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer/resend_otp', $r->resendOtp()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer/disable_otp', $r->disableOtp()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer/enable_otp', $r->enableOtp()[RouteInterface::ENDPOINT_KEY]);
        $this->assertEquals('/transfer/disable_otp_finalize', $r->disableOtpFinalize()[RouteInterface::ENDPOINT_KEY]);
    }

    public function testMethods()
    {
        $r = new Transfer();
        $this->assertEquals(RouteInterface::POST_METHOD, $r->initiate()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->getList()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::GET_METHOD, $r->fetch()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->finalizeTransfer()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->resendOtp()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->disableOtp()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->enableOtp()[RouteInterface::METHOD_KEY]);
        $this->assertEquals(RouteInterface::POST_METHOD, $r->disableOtpFinalize()[RouteInterface::METHOD_KEY]);
    }
}
