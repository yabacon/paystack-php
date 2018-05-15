<?php
namespace Yabacon\Paystack\Tests;

class AutoloadTest extends \PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        global $paystack_autoloader;
        require_once(__DIR__ . '/../src/autoload.php');
        $paystack_autoloader('Yabacon\\Paystack\\Routes\\Invoice');
    }
}
