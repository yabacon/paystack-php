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
email and amount are the most common compulsory parameters. Do send a unique email per customer. If your customers do not provide a unique email, please devise a strategy to set one for each of them. Any of those below work fine. The amount we accept on all endpoint are in kobo and must be an integer value. For instance, to accept 456 naira, 78 kobo, please send 45678 as the amount.

### 2. Initialize a transaction
Initialize a transaction by calling our API.

```php
<?php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'amount'=>$amount,
    'email'=>$email,
  ]),
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Bearer SECRET_KEY",
    "cache-control: no-cache",
    "content-type: application/json"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
	// there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}

$tranx = json_decode($response);

if(!$tranx->status){
  // there was an error from the API
  die('API returned error: ' . $tranx->message);
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
<?php
// filename: handle-webhook.php

// Retrieve the request's body
$body = @file_get_contents("php://input");
$signature = (isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']) ? $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] : '');

/* It is a good idea to log all events received. Add code *
 * here to log the signature and body to db or file       */

if (!$signature) {
    // only a post with paystack signature header gets our attention
    exit();
}

define('PAYSTACK_SECRET_KEY','sk_xxxx_xxxxxx');
// confirm the event's signature
if( $signature !== hash_hmac('sha512', $body, PAYSTACK_SECRET_KEY) ){
  // silently forget this ever happened
  exit();
}

http_response_code(200);
// parse event (which is json string) as object
// Give value to your customer but don't give any output
// Remember that this is a call from Paystack's servers and 
// Your customer is not seeing the response here at all
$event = json_decode($body);
switch($event->event){
    // charge.success
    case 'charge.success':
        // TIP: you may still verify the transaction
    		// before giving value.
        break;
}
exit();
```

### 4. Verify Transaction
After we redirect to your callback url, please verify the transaction before giving value.

```php
<?php
// filename: callback.php

$curl = curl_init();
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
if(!$reference){
  die('No reference supplied');
}

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Bearer SECRET_KEY",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
	// there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}

$tranx = json_decode($response);

if(!$tranx->status){
  // there was an error from the API
  die('API returned error: ' . $tranx->message);
}

if('success' == $tranx->data->status){
  // transaction was successful...
  // please check otehr things like whether you already gave value for this ref
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
