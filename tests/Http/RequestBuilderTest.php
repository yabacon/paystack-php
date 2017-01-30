<?php
namespace Yabacon\Paystack\Tests\Http;

use Yabacon\Paystack\Http\RequestBuilder;
use Yabacon\Paystack;
use Yabacon\Paystack\Contracts\RouteInterface;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testMoveArgsToSentargs()
    {
        $p = new Paystack('sk_');
        $interface = ['args'=>['id']];
        $payload = ['id'=>1,'reference'=>'something'];
        $sentargs = [];
        $rb = new RequestBuilder($p, $interface, $payload, $sentargs);

        $rb->moveArgsToSentargs();
        $this->assertEquals(1, $rb->sentargs['id']);
        $this->assertEquals(1, count($rb->payload));
    }

    public function testPutArgsIntoEndpoint()
    {
        $p = new Paystack('sk_');
        $rb = new RequestBuilder($p, null, [], ['reference'=>'some']);
        $endpoint = 'verify/{reference}';

        $rb->putArgsIntoEndpoint($endpoint);
        $this->assertEquals('verify/some', $endpoint);
    }
}
