<?php

require dirname(__FILE__) . '/Paystack.php';
\Paystack\Paystack::registerAutoloader();

$paystack = new \Paystack\Paystack('sk_test_40899660eac2be0a6a6915f6ba32f81bc8bac143');

// print_r($paystack->customer(12));
// print_r($paystack->customer->list());
// print_r($paystack->transaction->list(['perPage'=>5,'page'=>2]));
print_r($paystack->customer->create(['first_name'=>'Dafe', 'last_name'=>'Aba', 'email'=>"dafe@aba.c", 'phone'=>'08012345678']));
// print_r($paystack->transaction->initialize(['reference'=>'unique_refencecode', 'amount'=>'120000', 'email'=>'dafe@aba.c']));
// print_r($paystack->transaction->verify([],['reference'=>'unique_refencecode']));
// print_r($paystack->transaction(1100000098));
// 
// print_r($paystack->transaction->totals());
