<?php
namespace Yabacon\Paystack\Tests\Helpers;

use Yabacon\Paystack\Helpers\Caller;
use Yabacon\Paystack;
use Yabacon\Paystack\Contracts\RouteInterface;

class CallerTest extends \PHPUnit_Framework_TestCase
{
    public function testMoveArgsToSentargs()
    {
        $p = new Paystack('sk_');
        $c = new Caller($p);
        $interface = ['args'=>['id']];
        $payload = ['id'=>1,'reference'=>'something'];
        $sentargs = [];

        $c->moveArgsToSentargs($interface, $payload, $sentargs);
        $this->assertEquals(1, $sentargs['id']);
        $this->assertEquals(1, count($payload));
    }

    public function testPutArgsIntoEndpoint()
    {
        $p = new Paystack('sk_');
        $c = new Caller($p);
        $endpoint = 'verify/{reference}';
        $sentargs = ['reference'=>'some'];

        $c->putArgsIntoEndpoint($endpoint, $sentargs);
        $this->assertEquals('verify/some', $endpoint);
    }
}
