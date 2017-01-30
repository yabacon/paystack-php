<?php
namespace Yabacon\Paystack\Tests\Http;

use Yabacon\Paystack\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $r = new Response();
        $this->assertNotNull($r);
    }
}
