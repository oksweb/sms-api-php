# OKS SMS PHP Library

[![Build Status](https://travis-ci.org/stripe/stripe-php.svg?branch=master)](https://travis-ci.org/stripe/stripe-php)
[![Total Downloads](https://poser.pugx.org/oksweb/sms-api-php/downloads.svg)](https://packagist.org/packages/stripe/stripe-php)
[![Code Coverage](https://coveralls.io/repos/stripe/stripe-php/badge.svg?branch=master)](https://coveralls.io/r/stripe/stripe-php?branch=master)

The OKSWeb SMS API PHP library provides convenient access to the OKS SMS API from
applications written in the PHP language.

## Requirements

PHP 5.6.0 and later.

## Composer

You can install the library via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require oksweb/api-sms-php
```

Don't forget to autoload it using Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php)
-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php) (Multibyte String)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Simple usage looks like:

```php
require_once('vendor/autoload.php');

$OKSWebSMSGateway = new \OKSWeb\SMS\Gateway(
    'mKdG9gjFdmuPWbFAFn9wrAi3bwJ8V6QpNcU7sCQBF9SKtfcSbE8DSgy1Mg2fbjpp', // API Key
    1, // Server ID default to 1
);

$balance = $OKSWebSMSGateway->checkBalance();

$response = $OKSWebSMSGateway->sendQuickSMS(
    '+213512345678', // This is the phone number in the E.164 Format !
    'Hello World !', // Your message
);
```

## TODO

- Documentation
- Testes
- Atomisation


## Development

Get [Composer][composer].

Install dependencies:

```bash
composer install
```

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), then you can run the test suite:

```bash
./vendor/bin/phpunit
```

The library uses [PHP CS Fixer][php-cs-fixer] for code formatting. Code must be formatted before PRs are submitted, otherwise CI will fail. Run the formatter with:

```bash
./vendor/bin/php-cs-fixer fix -v .
```
