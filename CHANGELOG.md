# Changelog

All Notable changes to `paystack-php` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 2.1.22 - 2018-05-15

### Added
- Invoice route

## 2.1.3 - 2017-01-30

### Changes
- Spread logic into several classes for improved Unit Testing

### Added
- Event
- Fees
- Subaccount Route

## 2.0.3 - 2016-12-11

### Changes
- To do a direct register_autoload, include autoload.php

### Deprecated
- ~Paystack::registerAutoloader();~

## 2.0 - 2016-04-26

### Changes
- Calls will return an Object of stdClass or throw a Paystack API/cURL error instead of
an array as in version 1
- Root namespace is now Yabacon instead of YabaCon

### Added
- Pages
- Subscriptions
- Use ->fetch to get a single item or call singular form with id/code
- Use ->list to get a list of items or call plural form with paging parameters

## 2.0.3 - 2016-12-11

### Changes
- Spread logic into several classes for improved Unit Testing

### Added
- Event
- Fees

### Added usage of TLSv1.2
CURL default SSL version TLSv1.2
Update Requirements for Curl, OpenSSL and PHP
define `CURL_SSLVERSION_TLSv1_2` as 6 if not found, to avoid not defined error

## 1.0.2 - 2016-03-10

### Added usage of TLSv1.2
CURL default SSL version TLSv1.2
Update Requirements for Curl, OpenSSL and PHP
define `CURL_SSLVERSION_TLSv1_2` as 6 if not found, to avoid not defined error

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
