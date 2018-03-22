<?php
namespace Yabacon\Paystack\Tests\Http;

use Yabacon\Paystack\Http\RequestBuilder;
use Yabacon\Paystack;
use Yabacon\Paystack\Contracts\RouteInterface;
use Yabacon\Paystack\Routes\Customer;
use Yabacon\Paystack\Routes\Transaction;

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

    public function testBuild()
    {
        $p = new Paystack('sk_');
        $params = ['email'=>'some@ema.il'];
        $rb = new RequestBuilder($p, Customer::create(), $params);

        $r = $rb->build();
        $this->assertEquals('https://api.paystack.co/customer', $r->endpoint);
        $this->assertEquals('Bearer sk_', $r->headers['Authorization']);
        $this->assertEquals('post', $r->method);
        $this->assertEquals(json_encode($params), $r->body);

        $params = ['perPage'=>10];
        $rb = new RequestBuilder($p, Customer::getList(), $params);

        $r = $rb->build();
        $this->assertEquals('https://api.paystack.co/customer?perPage=10', $r->endpoint);
        $this->assertEquals('Bearer sk_', $r->headers['Authorization']);
        $this->assertEquals('get', $r->method);
        $this->assertEmpty($r->body);

        $args = ['reference'=>'some-reference'];
        $rb = new RequestBuilder($p, Transaction::verify(), [], $args);

        $r = $rb->build();
        $this->assertEquals('https://api.paystack.co/transaction/verify/some-reference', $r->endpoint);
        $this->assertEquals('Bearer sk_', $r->headers['Authorization']);
        $this->assertEquals('get', $r->method);
        $this->assertEmpty($r->body);
    }
}
