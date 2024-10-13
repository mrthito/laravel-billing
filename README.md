![Banner](https://banners.beyondco.de/Laravel%20Billing.png?theme=light&packageManager=composer+require&packageName=mrthito%2Flaravel-billing&pattern=ticTacToe&style=style_1&description=Advanced+Billing+Portal+for+Laravel&md=1&showWatermark=0&fontSize=125px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Advanced Billing Portal for Laravel

> [!WARNING]
> This package is currently under development and not recommended to use this package under any curcumstances. Many of the features may change in the future or will be removed. If you want to contribute to this package, you can fork this repository and make a pull request.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mrthito/laravel-billing.svg?style=flat-square)](https://packagist.org/packages/mrthito/laravel-billing)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mrthito/laravel-billing/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mrthito/laravel-billing/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mrthito/laravel-billing/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/mrthito/laravel-billing/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mrthito/laravel-billing.svg?style=flat-square)](https://packagist.org/packages/mrthito/laravel-billing)

Laravel Billing is a package that provides a billing portal for your Laravel application. It provides a simple and easy to use interface for managing your billing and subscription. Currently, this package is under development and not recommended to use in any environment.

## Installation

You can install the package via composer:

```bash
composer require mrthito/laravel-billing
```

You can install the package scaffolding using the following command:

```bash
php artisan install:laravel-billing
```

This command will publish the migration files and run the migration. You can also run the migration manually using the following command:

```bash
php artisan migrate
```

## Usage

```php
use MrThito\LaravelBilling\Traits\UseBilling;
class User extends Authenticatable
{
    use UseBilling;
    // ...

}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Prashant Rijal](https://github.com/mrthito)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
