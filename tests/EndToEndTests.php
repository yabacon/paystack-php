<?php
require dirname(__FILE__) . '/../src/Paystack.php';
\YabaCon\Paystack::registerAutoloader();

$paystack = new \YabaCon\Paystack('sk_test_40899660eac2be0a6a6915f6ba32f81bc8bac143');
//if (true) {
//    die();
//}
print_r($paystack->customer());
//print_r($paystack->ff(3447));
// print_r($paystack->customer->dontlist());
// print_r($paystack->customer->update(['id'=>3447,'first_name'=>'Gopher']));
// print_r($paystack->customer->update(['id'=>,'first_name'=>, 'last_name'=>, 'email'=>, 'phone']));
// print_r($paystack->transaction->list(['perPage'=>1,'page'=>1]));
// print_r($paystack->customer->create([
//   'first_name'=>'Dafe',
//   'last_name'=>'Aba',
//   'email'=>"dafe@aba.c",
//   'phone'=>'08012345678']));
// print_r($paystack->transaction->initialize([
//     'reference'=>'unique_refencecode',
//     'amount'=>'120000',
//     'email'=>'dafe@aba.c']));
// print_r($paystack->transaction->verify(['reference'=>'unique_refencecode','dummy'=>'dum','id'=>3]));
// print_r($paystack->transaction(4538));
//
// print_r($paystack->transaction->totals());
