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
      $tranx = $paystack->transaction->initialize([
        'amount'=>$amount,       // in kobo
        'email'=>$email,         // unique to customers
        'reference'=>$reference, // unique to transactions
      ]);
    } catch(\Yabacon\Paystack\Exception\ApiException $e){
      print_r($e->getResponseObject());
      die($e->getMessage());
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
            if('success' === $event->obj->data->status){
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
    $paystack = new Yabacon\Paystack(SECRET_KEY);
    try
    {
      // verify using the library
      $tranx = $paystack->transaction->verify([
        'reference'=>$reference, // unique to transactions
      ]);
    } catch(\Yabacon\Paystack\Exception\ApiException $e){
      print_r($e->getResponseObject());
      die($e->getMessage());
    }

    if ('success' === $tranx->data->status) {
      // transaction was successful...
      // please check other things like whether you already gave value for this ref
      // if the email matches the customer who owns the product etc
      // Give value
    }
```

## 5. Closing Notes

Generally, to make an API request after constructing a paystack object, Make a call
 to the resource/method thus: `$paystack->{resource}->{method}()`; for gets,
  use `$paystack->{resource}(id)` and to list resources: `$paystack->{resource}s()`.

Currently, we support: 'customer', 'page', 'plan', 'subscription', 'transaction' and 'subaccount'. Check
our API reference([link-paystack-api-reference][link-paystack-api-reference]) for the methods supported. To specify parameters, send as an array.

Check [SAMPLES](SAMPLES.md) for more sample calls

## Extras

There are classes that should help with some tasks developers need to do on Paystack often:

### Fee

This class works with an amount and your Paystack fees. To use, create a new Fee object

```php
    $fee = new Yabacon\Paystack\Fee();
```
Configure it:

```php
    $fee->withPercentage(0.015);        // 1.5%
    $fee->withAdditionalCharge(10000);  // plus 100 NGN
    $fee->withThreshold(250000);        // when total is above 2,500 NGN
    $fee->withCap(200000);              // capped at 2000
```

Calculate fees
```php
    $chargeOn300naira = $fee->calculateFor(30000);
    $chargeOn7000naira = $fee->calculateFor(700000);
```

To know what to send to the API when you want to be settled a particular amount
```php
    $iWant100Naira = $fee->addFor(10000);
    $iWant4000Naira = $fee->addFor(400000);
```

### Event

This class helps you capture and our events:
```php
    $event = Yabacon\Paystack\Event::capture();
```

Verify it against a key:
```php
    if($event->validFor($mySecretKey))
    {
        // awesome
    }
```

Discover the key that owns it (IN case same webhook receives for several integrations). This can
come in handy for multi-tenant systems. Or simply if you want to have same test and live webhook url.
```php
    $my_keys = [
                'live'=>'sk_live_blah',
                'test'=>'sk_test_blah',
              ];
    $owner = $event->discoverOwner($my_keys);
    if(!$owner){
        // None of my keys matched the event's signature
        die();
    }
```

To forward the event to another url. In case you want other systems to be notified of exact same event.
```php
    $evt->forwardTo('http://another-webhook.url');
```

### MetadataBuilder

This class helps you build valid json metadata strings to be sent when making transaction requests.
```php
    $builder = new MetadataBuilder();
```

#### Add metadata

Add MetaData by calling the `withKeyName` magic function (These will not be shown on dashboard).
Do not use `CustomFields` as a KeyName

```php
    $builder->withQuoteId(10); // will add as quote_id: 10 unless you turn auto_snake_case off
    $builder->withMobileNumber(08012345678); // will add as mobile_number: 08012345678
    $builder->withCSRF('dontacceptpaymentunlessthismatches');
```

To turn off automatic snake-casing of Key names, do:

```php
    MetadataBuilder::$auto_snake_case = false;
```

before you start adding metadata to the `$builder`.

#### Add Custom Fields

Add Custom Fields by calling the `withCustomField` function (These will shown on dashboard).

```php
    $builder->withCustomField('Mobile Number', '080123456789');
    $builder->withCustomField('See Me', 'I\'m Visible on your Dashboard');
```

#### Build JSON

Finally call `build()` to get your JSON metadata string.

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
- [Akachukwu Okafor](https://github.com/kasoprecede47)
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
[link-paystack-api-reference]: https://developers.paystack.co/reference
