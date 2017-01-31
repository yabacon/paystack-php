<?php
// quick and dirty testing
error_reporting(-1);
//require_once __DIR__ . '/../vendor/autoload.php';

// $dotenv = new Dotenv\Dotenv(dirname(dirname(__FILE__)));
// $dotenv->load();

require_once dirname(__DIR__) . '/src/autoload.php';
$paystack = new \Yabacon\Paystack(("sk_test_a89e7310342b8295dce0f239b55d7fe3545e1887"));

//$evt = Yabacon\Paystack\Event::capture();

// print_r($evt->forwardTo('http://localhost'));
// $paystack2 = new \Yabacon\Paystack(getenv("PAYSTACK_SECRET_KEY_FAKE"));
// $paystack2->useGuzzle();
// $k = $paystack2->customer->list();
// print_r(json_decode($k->getbody(), true));
// PLN_qs5x408lj9qkuvx
//

//print_r($paystack->customer('CUS_j18angz96uaepmd'));
if (false) {
    print_r($paystack->customer(18920));
    print_r($paystack->subscriptions());
    print_r($paystack->pages());
    print_r($paystack->plans());
    print_r($paystack->transactions());
    print_r($paystack->transaction(111));
    print_r($paystack->plan(111));
    print_r($paystack->customer('CUS_xxx'));
    print_r($paystack->subscription('SUB_xxx'));
    print_r(
        $paystack->transaction->initialize(
            [
            'plan'=>'PLN_xxx',
            'email'=>'e@m.ail']
        )
    );
    print_r($paystack->subscription->list());
    print_r($paystack->customer(1));
    print_r($paystack->ff(3447));
    print_r($paystack->customer->dontlist());
    print_r($paystack->customer->update(['id'=>3447,'first_name'=>'Gopher']));
    print_r($paystack->transaction->list(['perPage'=>1,'page'=>1]));
    print_r($paystack->customer->list(['perPage'=>1,'page'=>1]));
    print_r(
        $paystack->plan->create(
            [
                'name'=>'testing testing',
                'description'=>'testing tester',
                'amount'=>10000, // in kobo
                'interval'=>'daily',
                'send_invoices'=>true,
                'send_sms'=>true,
                'currency'=>'NGN'
            ]
        )
    );
    print_r(
        $paystack->customer->create(
            [
            'first_name'=>'Dafe',
            'last_name'=>'Aba',
            'email'=>"dafe@aba.c",
            'phone'=>'08012345678']
        )
    );
    print_r(
        $paystack->transaction->initialize(
            [
            'callback_url'=>'http://example.com/verify.php?pageid=xxx&sample=1',
            'amount'=>'120000', // This is 1 thousand 200 naira
            'email'=>'dafe@aba.c']
        )
    );
    print_r($paystack->transaction->verify(['reference'=>'xxxx']));
    print_r($paystack->transaction(4538));
    print_r($paystack->transaction->totals());
}
