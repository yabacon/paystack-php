<?php
namespace Yabacon\Paystack\Test\Mock;

class EventTestDouble extends \Yabacon\Paystack\Event
{
    const DUMMY_RAW = '{"event":"just.testing","data":{"domain":"test","invoice_co'
    . 'de":"INV_mn8lm53yl17w19r","amount":150000,"period_start":"2017-01-13T00:00:'
    . '00.000Z","period_end":"2017-02-12T23:59:59.000Z","status":"success","paid":'
    . 'true,"paid_at":"2017-01-13T00:00:16.000Z","description":null,"authorization'
    . '":{"authorization_code":"AUTH_1nv4LiD","bin":"506506","last4":"7777","exp_m'
    . 'onth":"06","exp_year":"2099","card_type":"mastercard DEBIT","bank":"Capital'
    . ' Corp","country_code":"NG","brand":"mastercard","reusable":true},"subscript'
    . 'ion":{"status":"active","subscription_code":"SUB_youwishiwas","email_token"'
    . ':"youwishitis","amount":150000,"cron_expression":"0 0 13 * *","next_payment'
    . '_date":"2017-02-13T00:00:00.000Z","open_invoice":null},"customer":{"first_n'
    . 'ame":null,"last_name":null,"email":"free@free_basics.com","customer_code":"'
    . 'CUS_youwhistle","phone":null,"metadata":null,"risk_action":"default"},"tran'
    . 'saction":{"reference":"someref","status":"success","amount":150000,"currenc'
    . 'y":"NGN"},"created_at":"2017-01-13T00:00:02.000Z"}}';
    const DUMMY_SIGNATURE = '0ef509bb72218531a7d0aa58d2b8dcd93f63f7a4d1f8e2e36eadd'
    . '4f0a19455a92f2ca57a57bfe98dc25f91c47b1221343d61fdd5fd2b7c41b8466cbe5ebd4974';

    public static function dummyCapture()
    {
        $evt = new EventTestDouble();
        $evt->raw = EventTestDouble::DUMMY_RAW;
        $evt->signature = EventTestDouble::DUMMY_SIGNATURE;
        $evt->loadObject();
        return $evt;
    }

    public function forwardTo($url)
    {
        $packed = $this->package();
        $packed->endpoint = $url;
        return true;
    }
}
