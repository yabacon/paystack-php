<?php
namespace Yabacon\Paystack\Tests;

use Yabacon\Paystack;
use Yabacon\Paystack\Helpers\Router;
use \Yabacon\Paystack\Exception\ValidationException;

class PaystackTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializeWithInvalidSecretKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $r = new Paystack('p');
    }

    public function testSetUseGuzzle()
    {
        $r = new Paystack('sk_');
        $r->useGuzzle();
        $this->assertTrue($r->use_guzzle);
    }

    public function testGetShouldBringRouter()
    {
        $r = new Paystack('sk_');
        $this->assertInstanceOf(Router::class, $r->customer);
        $this->expectException(ValidationException::class);
        $this->assertNull($r->nonexistent);
    }

    public function testFetchWithInvalidParams()
    {
        $r = new Paystack('sk_');
        $this->expectException(\InvalidArgumentException::class);
        $this->assertNull($r->nonexistent());
        $this->expectException(ValidationException::class);
        $this->assertNull($r->nonexistent(1));
        $this->expectException(ValidationException::class);
        $this->assertNull($r->customer());
        $this->expectException(ValidationException::class);
        $this->assertNull($r->customers(1));
    }
}
