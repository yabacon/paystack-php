#Sample calls 

Assumes that you already installed and configured Yabacon\Paystack. And that you have created and 
configured the $paystack object as you want. Check [README](README.md) for details.

``` php
// Make a call to the resource/method
// $paystack->{resource}->{method}(); 
// for gets, use $paystack->{resource}(id)

// customer
$paystack->customer(12);
$paystack->customer->list();
$paystack->customer->create([
                'first_name'=>'name',
                'last_name'=>'name',
                'email'=>'email',
                'phone'=>'phone'
              ]);
$paystack->customer->update([
                'id'=>233,
                'first_name'=>'name',
                'last_name'=>'name',
                'email'=>'email',
                'phone'=>'phone'
              ]);
$paystack->customer->list(['perPage'=>5,'page'=>2]); // list the second page at 5 customers per page

// plan
$paystack->plan(12);
$paystack->plan->list();
$paystack->plan->create([
                'name'=>'name',
                'description'=>'Describe at length',
                'amount'=>1000, // in kobo
                'interval'=>7,
                'send_invoices'=>true,
                'send_sms'=>true,
                'hosted_page'=>'url',
                'hosted_page_url'=>'url',
                'hosted_page_summary'=>'details',
                'currency'=>'NGN'
              ]);
$paystack->plan->update([
                'name'=>'name',
                'description'=>'Describe at length',
                'amount'=>1000, // in kobo
                'interval'=>7,
                'send_invoices'=>true,
                'send_sms'=>true,
                'hosted_page'=>'url',
                'hosted_page_url'=>'url',
                'hosted_page_summary'=>'details',
                'currency'=>'NGN'
              ],['id'=>233]);
$paystack->plan->list(['perPage'=>5,'page'=>2]); // list the second page at 5 plans per page

// transaction
$paystack->transaction(12);
$paystack->transaction->list();
$paystack->transaction->initialize([
                'reference'=>'unique',
                'amount'=>19000, // in kobo
                'email'=>'e@ma.il', 
                'plan'=>1 // optional, don't include unless it has a value
              ]);
$paystack->transaction->charge([
                'reference'=>'unique',
                'authorization_code'=>'auth_code',
                'email'=>'e@ma.il',
                'amount'=>1000 // in kobo
              ]);
$paystack->transaction->chargeToken([
                'reference'=>'unique',
                'token'=>'pstk_token',
                'email'=>'e@ma.il',
                'amount'=>1000 // in kobo
              ]);
$paystack->transaction->list(['perPage'=>5,'page'=>2]); // list the second page at 5 transactions per page

$paystack->transaction->verify([
                'reference'=>'unique_refencecode'
                ]);
$paystack->transaction->totals();


```