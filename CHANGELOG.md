# Changelog

All Notable changes to `paystack-php` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

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
