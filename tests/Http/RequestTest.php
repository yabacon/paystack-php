<?php
namespace Yabacon\Paystack\Tests\Http;

use Yabacon\Paystack\Http\Request;
use Yabacon\Paystack\Http\Response;
use Yabacon\Paystack;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $r = new Request();
        $this->assertNotNull($r);
    }

    public function testAllApiRequestsMustHaveJsonHeader()
    {
        $p = new Paystack('sk_');
        $r = new Request($p);
        $this->assertEquals('application/json', $r->headers['Content-Type']);
        $rNonApi = new Request();
        $this->assertFalse(array_key_exists('Content-Type', $rNonApi->headers));
    }

    public function testGetResponse()
    {
        $rq = new Request();
        $rp = $rq->getResponse();
        $this->assertNotNull($rp);
        $this->assertInstanceOf(Response::class, $rp);
    }

    public function testFlattenedHeadersAndThatOnlyContentTypeAddedByDefaultWhenPaystackObjectPresent()
    {
        $p = new Paystack('sk_');
        $rq = new Request($p);
        $hs = $rq->flattenedHeaders();
        $this->assertEquals(1, count($hs));
        $this->assertNotNull($hs[0]);
    }
}
