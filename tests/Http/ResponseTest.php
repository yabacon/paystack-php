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

    public function testWrapUpForApiOkayNoStatusWithErrors()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = true;
        $r->body = '{"message": "A validation error has occured","errors": {"email"';
        $r->body .= ': [{"rule": "required","message": "Email is required"},{"rule"';
        $r->body .= ': "email","message": "Email must be valid"}],"name": [{"rule":';
        $r->body .= ' "required","message": "Name is required"}]}}';

        try {
            $resp = $r->wrapUp();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ApiException::class, $e);
            $this->assertInstanceOf(\stdClass::class, $e->getResponseObject()->errors);
        }
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
        $r->body = '{"status":"0","message":"I failed on Api"}';

        $this->expectException(ApiException::class);
        $resp = $r->wrapUp();
    }

    public function testWrapUpForApiOkayNoStatus()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = true;
        $r->body = '{"message":"I failed on Api"}';

        try {
            $resp = $r->wrapUp();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ApiException::class, $e);
            $this->assertEquals("Paystack Request failed with response: 'I failed on Api'", $e->getMessage());
        }
    }

    public function testWrapUpForApiOkayNoStatusNoMessage()
    {
        $r = new Response();
        $r->okay = true;
        $r->forApi = true;
        $r->body = '{"errors":"I failed on Api"}';

        try {
            $resp = $r->wrapUp();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ApiException::class, $e);
            $expectedResponse = "Paystack Request failed with response: '{\"errors\":\"I failed on Api\"}'";
            $this->assertEquals($expectedResponse, $e->getMessage());
        }
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
