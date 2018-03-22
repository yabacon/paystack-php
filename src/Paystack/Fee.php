<?php
namespace Yabacon\Paystack;

class Fee
{
    const DEFAULT_PERCENTAGE = 0.015;
    const DEFAULT_ADDITIONAL_CHARGE = 10000;
    const DEFAULT_THRESHOLD = 250000;
    const DEFAULT_CAP = 200000;

    public static $default_percentage = Fee::DEFAULT_PERCENTAGE;
    public static $default_additional_charge = Fee::DEFAULT_ADDITIONAL_CHARGE;
    public static $default_threshold = Fee::DEFAULT_THRESHOLD;
    public static $default_cap = Fee::DEFAULT_CAP;

    private $percentage;
    private $additional_charge;
    private $threshold;
    private $cap;

    private $chargeDivider;
    private $crossover;
    private $flatlinePlusCharge;
    private $flatline;

    public function __construct()
    {
        $this->percentage = Fee::$default_percentage;
        $this->additional_charge = Fee::$default_additional_charge;
        $this->threshold = Fee::$default_threshold;
        $this->cap = Fee::$default_cap;
        $this->__setup();
    }

    public function withPercentage($percentage)
    {
        $this->percentage = $percentage;
        $this->__setup();
    }

    public static function resetDefaults()
    {
        Fee::$default_percentage = Fee::DEFAULT_PERCENTAGE;
        Fee::$default_additional_charge = Fee::DEFAULT_ADDITIONAL_CHARGE;
        Fee::$default_threshold = Fee::DEFAULT_THRESHOLD;
        Fee::$default_cap = Fee::DEFAULT_CAP;
    }

    public function withAdditionalCharge($additional_charge)
    {
        $this->additional_charge = $additional_charge;
        $this->__setup();
    }

    public function withThreshold($threshold)
    {
        $this->threshold = $threshold;
        $this->__setup();
    }

    public function withCap($cap)
    {
        $this->cap = $cap;
        $this->__setup();
    }

    private function __setup()
    {
        $this->chargeDivider = $this->__chargeDivider();
        $this->crossover = $this->__crossover();
        $this->flatlinePlusCharge = $this->__flatlinePlusCharge();
        $this->flatline = $this->__flatline();
    }

    private function __chargeDivider()
    {
        return 1 - $this->percentage;
    }

    private function __crossover()
    {
        return ($this->threshold * $this->chargeDivider) - $this->additional_charge;
    }

    private function __flatlinePlusCharge()
    {
        return ($this->cap - $this->additional_charge) / $this->percentage;
    }

    private function __flatline()
    {
        return $this->flatlinePlusCharge - $this->cap;
    }

    public function addFor($amountinkobo)
    {
        if ($amountinkobo > $this->flatline) {
            return intval(ceil($amountinkobo + $this->cap));
        } elseif ($amountinkobo > $this->crossover) {
            return intval(ceil(($amountinkobo + $this->additional_charge) / $this->chargeDivider));
        } else {
            return intval(ceil($amountinkobo / $this->chargeDivider));
        }
    }

    public function calculateFor($amountinkobo)
    {
        $fee = $this->percentage * $amountinkobo;
        if ($amountinkobo >= $this->threshold) {
            $fee += $this->additional_charge;
        }
        if ($fee > $this->cap) {
            $fee = $this->cap;
        }
        return intval(ceil($fee));
    }
}
