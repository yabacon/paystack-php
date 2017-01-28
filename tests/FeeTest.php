<?php
namespace Yabacon\Paystack\Tests;

use Yabacon\Paystack\Fee;

class FeeTest extends \PHPUnit_Framework_TestCase
{
    public function testAddFor()
    {
        $fee = new Fee();
        $this->assertEquals(10153, $fee->addFor(10000));
        $this->assertEquals(1000000, $fee->addFor(975000));

        $fee->withPercentage(0.014);
        $this->assertEquals(10142, $fee->addFor(10000));
        $this->assertEquals(998986, $fee->addFor(975000));

        $fee->withThreshold(2000000);
        $this->assertEquals(10142, $fee->addFor(10000));
        $this->assertEquals(988844, $fee->addFor(975000));
        $this->assertEquals(3052739, $fee->addFor(3000000));

        $fee->withThreshold(Fee::DEFAULT_THRESHOLD);
        $fee->withAdditionalCharge(0);
        $this->assertEquals(10142, $fee->addFor(10000));
        $this->assertEquals(988844, $fee->addFor(975000));

        $fee->withCap(3000);
        $this->assertEquals(10142, $fee->addFor(10000));
        $this->assertEquals(978000, $fee->addFor(975000));
    }

    public function testDefaultParams()
    {
        $fee = new Fee();
        $this->assertEquals(10153, $fee->addFor(10000));
        $this->assertEquals(10000, $fee->addFor(9850));
        $this->assertEquals(1025381, $fee->addFor(1000000));
        $this->assertEquals(1000000, $fee->addFor(975000));

        $this->assertEquals(150, $fee->calculateFor(10000));
        $this->assertEquals(24775, $fee->calculateFor(985000));
        $this->assertEquals(25000, $fee->calculateFor(1000000));
        $this->assertEquals(24625, $fee->calculateFor(975000));
    }

    public function testResetDefaults()
    {
        Fee::$default_percentage = 0.039;
        Fee::$default_cap = 10000000000;
        Fee::$default_threshold = 250000;
        Fee::$default_additional_charge = 10000;

        $fee = new Fee();
        Fee::resetDefaults();
        $feeAfterReset = new Fee();

        $this->assertEquals(10406, $fee->addFor(10000));
        $this->assertEquals(10153, $feeAfterReset->addFor(10000));
        $this->assertEquals(1024974, $fee->addFor(975000));
        $this->assertEquals(1000000, $feeAfterReset->addFor(975000));
    }

    public function testCalculateFor()
    {
        $fee = new Fee();
        $this->assertEquals(150, $fee->calculateFor(10000));
        $this->assertEquals(24625, $fee->calculateFor(975000));

        $fee->withPercentage(0.014);
        $this->assertEquals(140, $fee->calculateFor(10000));
        $this->assertEquals(23650, $fee->calculateFor(975000));

        $fee->withThreshold(2000000);
        $this->assertEquals(140, $fee->calculateFor(10000));
        $this->assertEquals(13650, $fee->calculateFor(975000));
        $this->assertEquals(52000, $fee->calculateFor(3000000));

        $fee->withAdditionalCharge(0);
        $this->assertEquals(140, $fee->calculateFor(10000));
        $this->assertEquals(13650, $fee->calculateFor(975000));

        $fee->withCap(3000);
        $this->assertEquals(140, $fee->calculateFor(10000));
        $this->assertEquals(3000, $fee->calculateFor(975000));
        $this->assertEquals(3000, $fee->calculateFor(3000000));
    }
}
