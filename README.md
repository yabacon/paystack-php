# paystack-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP API wrapper for [Paystack](https://paystack.co/).

[![Paystack](img/paystack.png?raw=true "Paystack")](https://paystack.co/)

## Requirements
- Curl 7.34.0 or more recent (Unless using Guzzle)
- PHP 5.4.0 or more recent
- OpenSSL v1.0.1 or more recent

## Install

### Via Composer

``` bash
$ composer require yabacon/paystack-php
```

### Via download

Download a release version from the [releases page](https://github.com/yabacon/paystack-php/releases).
Extract, then:
``` php
require 'path/to/src/autoload.php';
```

## IMPORTANT
Version 2 is not compatible with version 1 code! It throws an error if there's problem error in cURL
or if the Paystack API gives a false status in the response body.

## Usage

Do a redirect to the authorization URL received from calling the transaction/initialize endpoint. This URL is valid for one time use, so ensure that you generate a new URL per transaction.

When the payment is successful, we will call your callback URL (as setup in your dashboard or while initializing the transaction) and return the reference sent in the first step as a query parameter.

If you use a test secret key, we will call your test callback url, otherwise, we'll call your live callback url.

### 0. Prerequisites
Confirm that your server can conclude a TLSv1.2 connection to Paystack's servers. Most up-to-date software have this capability. Contact your service provider for guidance if you have any SSL errors.
*Don't disable SSL peer verification!*

### 1. Prepare your parameters
`email` and `amount` are the most common compulsory parameters. Do send a unique email per customer. If your customers do not provide a unique email, please devise a strategy to set one for each of them. Any of those below work fine. The amount we accept on all endpoint are in kobo and must be an integer value. For instance, to accept `456 naira, 78 kobo`, please send `45678` as the amount.

### 2. Initialize a transaction
Initialize a transaction by calling our API.

```php
$paystack = new Yabacon\Paystack(SECRET_KEY);
try
{
  $tranx = $pasytack->transaction->initialize([
    'amount'=>$amount,       // in kobo
    'email'=>$email,         // unique to customers
    'reference'=>$reference, // unique to transactions
  ]);
} catch(Exception $e) {
  die($e->message);
}

// store transaction reference so we can query in case user never comes back
// perhaps due to network issue
save_last_transaction_reference($tranx->data->reference);

// redirect to page so User can pay
header('Location: ' . $tranx->data->authorization_url);
```

When the user enters their card details, Paystack will validate and charge the card. It will do all the below:

Redirect back to a callback_url set when initializing the transaction or on your dashboard at: https://dashboard.paystack.co/#/settings/developer . If neither is set, Customers see a Transaction was successful message.

Send a charge.success event to your Webhook URL set at: https://dashboard.paystack.co/#/settings/developer

If receipts are not turned off, an HTML receipt will be sent to the customer's email.

Before you give value to the customer, please make a server-side call to our verification endpoint to confirm the status and properties of the transaction.

### 3. Handle charge.success Event
We will post a charge.success event to the webhook URL set for your transaction's domain. If it was a live transaction, we will post to your live webhook url and vice-versa.

- if using .htaccess, remember to add the trailing / to the url you set.
- Do a test post to your URL and ensure the script gets the post body.
- Publicly available url (http://localhost cannot receive!)

```php
// Retrieve the request's body and parse it as JSON
$event = Yabacon\Paystack\Event::capture();
http_response_code(200);

/* It is a important to log all events received. Add code *
 * here to log the signature and body to db or file       */
openlog('MyPaystackEvents', LOG_CONS | LOG_NDELAY | LOG_PID, LOG_USER | LOG_PERROR);
syslog(LOG_INFO, $event->raw);
closelog();

/* Verify that the signature matches one of your keys*/
$my_keys = [
            'live'=>'sk_live_blah',
            'test'=>'sk_test_blah',
          ];
$owner = $event->discoverOwner($my_keys);
if(!$owner){
    // None of the keys matched the event's signature
    die();
}

// Do something with $event->obj
// Give value to your customer but don't give any output
// Remember that this is a call from Paystack's servers and 
// Your customer is not seeing the response here at all
switch($event->obj->event){
    // charge.success
    case 'charge.success':
        if('status'==$event->obj->data->status){
            // TIP: you may still verify the transaction
            // via an API call before giving value.
        }
        break;
}
```

### 4. Verify Transaction
After we redirect to your callback url, please verify the transaction before giving value.

```php
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
if(!$reference){
  die('No reference supplied');
}

// initiate the Library's Paystack Object
$paystack = new Paystack(SECRET_KEY);
try
{
  // verify using the library
  $tranx = $pasytack->transaction->verify([
    'reference'=>$reference, // unique to transactions
  ]);
} catch(Exception $e){
  die($e->message);
}

if ('success' == $tranx->data->status) {
  // transaction was successful...
  // please check other things like whether you already gave value for this ref
  // if the email matches the customer who owns the product etc
  // Give value
}
```

Check [SAMPLES](SAMPLES.md) for more sample calls

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CONDUCT](.github/CONDUCT.md) for details. Check our [todo list](TODO.md) for features already intended.

## Security

If you discover any security related issues, please email yabacon.valley@gmail.com instead of using the issue tracker.

## Credits

- [Yabacon][link-author]
- [Issa Jubril](https://github.com/masterp4dev)
- [Ibrahim Lawal](https://github.com/ibrahimlawal)
- [Opeyemi Obembe](https://github.com/kehers) - followed the style he employed in creating the [NodeJS Wrapper](https://github.com/kehers/paystack)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/yabacon/paystack-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/yabacon/paystack-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/yabacon/paystack-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/yabacon/paystack-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/yabacon/paystack-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/yabacon/paystack-php
[link-travis]: https://travis-ci.org/yabacon/paystack-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/yabacon/paystack-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/yabacon/paystack-php
[link-downloads]: https://packagist.org/packages/yabacon/paystack-php
[link-author]: https://github.com/yabacon
[link-contributors]: ../../contributors
