# paystack-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP API wrapper for [Paystack](https://paystack.co/).

[![Paystack](img/paystack.png?raw=true "Paystack")](https://paystack.co/)

## Install

Via Composer

``` bash
$ composer require yabacon/paystack-php
```

Via download

Download a release version from the [releases page](https://github.com/yabacon/paystack-php/releases). Extract, then:
``` php
require 'src/Paystack.php';
\YabaCon\Paystack::registerAutoloader();
```

## Usage

``` php
$paystack = new \YabaCon\Paystack('secret_key');

// Make a call to the resource/method
// paystack.{resource}.{method}
list($headers, $body) = $paystack->customer(12);
list($headers, $body) = $paystack->customer->list();
list($headers, $body) = $paystack->customer->list(['perPage'=>5,'page'=>2]); // list 5 customers per page

list($headers, $body) = $paystack->customer->create([
                          'first_name'=>'Dafe', 
                          'last_name'=>'Aba', 
                          'email'=>"dafe@aba.c", 
                          'phone'=>'08012345678'
                        ]);
list($headers, $body) = $paystack->transaction->initialize([
                          'reference'=>'unique_refencecode', 
                          'amount'=>'120000', 
                          'email'=>'dafe@aba.c'
                        ]);
list($headers, $body) = $paystack->transaction->verify([
                          'reference'=>'refencecode'
                        ]);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details. Check our [todo list](TODO.md) for features already intended.

## Security

If you discover any security related issues, please email yabacon.valley@gmail.com instead of using the issue tracker.

## Credits

- [YabaCon][link-author]
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
