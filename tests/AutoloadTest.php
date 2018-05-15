<?php
namespace Yabacon\Paystack\Tests;

class AutoloadTest extends \PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $paystack_autoloader = require(__DIR__ . '/../src/autoload.php');
        $paystack_autoloader('Yabacon\\Paystack\\Routes\\Invoice');
    }
}
