<?php
namespace Yabacon\Paystack\Tests\Http;

use Yabacon\Paystack\Http\Response;
use Yabacon\Paystack\Exception\ApiException;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $r = new Response();
        $this->assertNotNull($r);
    }

    public function testWrapUpForApiOkay()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = true;
        $r->body = '{"status":"1","data":{"status":"okay"}}';

        $resp = $r->wrapUp();
        $this->assertEquals('okay', $resp->data->status);
    }

    public function testWrapUpForApiOkayStatusFalse()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = true;
        $r->body = '{"status":"0","message":"I failed on Api","data":{"status":"okay"}}';

        $this->expectException(ApiException::class);
        $resp = $r->wrapUp();
        $this->assertEquals('I failed on Api', $resp->message);
    }

    public function testWrapUpForApiOkayBadJSON()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = true;
        $r->body = 'API didn\'t give JSON';

        $this->expectException(ApiException::class);
        $resp = $r->wrapUp();
    }

    public function testWrapUpForApiNotOkay()
    {
        $r = new Response();
        $r->okay = false;
        $r->forApi = true;
        $r->body = 'I failed before hitting API';

        $this->expectException(\Exception::class);
        $resp = $r->wrapUp();
    }

    public function testWrapUpNotForApiOkay()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = false;
        $r->body = 'I was not sent to API';

        $resp = $r->wrapUp();
        $this->assertEquals('I was not sent to API', $resp);
    }

    public function testWrapUpNotForApiNotOkay()
    {
        $r = new Response();
        $r->okay = false;
        $r->forApi = false;
        $r->body = 'I was not sent to API but I still failed';

        $resp = $r->wrapUp();
        $this->assertFalse($resp);
    }
}
