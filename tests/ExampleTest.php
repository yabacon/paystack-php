<?php

namespace Paystack\Test;

require dirname(__FILE__) . '/../src/Paystack.php';

\Paystack\Paystack::registerAutoloader();

class ExampleTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue() {
// Create a stub for the SomeClass class.
//         $stub = $this->getMockBuilder('Paystack\SkeletonClass')
//           ->getMock();
// 
// Configure the stub.
//         $stub->method('doSomething')
//           ->willReturn('foo');
// 
// Calling $stub->doSomething() will now return
// 'foo'.
//         $this->assertEquals('foo', $stub->doSomething());
        $this->assertTrue(true);
    }

}
