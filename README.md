# Make any class queue aware

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-queue-aware.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-queue-aware)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-queue-aware/run-tests?label=tests)](https://github.com/spatie/laravel-queue-aware/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-queue-aware/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-queue-aware/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-queue-aware.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-queue-aware)

Ever had a singleton in your Laravel application that you'd *really* like access to in a queued job? 

Let's take as an example an application with multiple tenants. To keep track of the tenant for the current request,
we have a `Tenant` object bound as a singleton in our application. When we dispatch a job to the queue, we want to keep track of the 
tenant that the job is for. But how can we do that? We don't want to store the tenant in the job itself, because that will quickly get
repetitive. It would be much better if *every* job was automatically aware of the tenant that was active at the time of dispatching.

Using this package, we can do exactly that, and it couldn't be simpler!

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-queue-aware.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-queue-aware)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-queue-aware
```

## Usage

To make the queue aware of an object, you can register the object in one of your service provider's `boot` methods. We'll use the
`Tenant` example from the previous section.

```php
public function boot()
{
    QueueAwareFacade::register(
        Tenant::class,
        fn () => Tenant::current()?->id,
        fn ($tenantId) => Tenant::find($tenantId),
    );
}
```

The `register` method of the `QueueAwareFacade` takes 3 parameters:
1) The key of the singleton in the Laravel container (usually the FQCN of the object)
2) A closure that returns the information needed to rebuild the singleton
3) A closure that is given the data returned in step 2 and returns a new instance of the singleton

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Luke Downing](https://github.com/lukeraymonddowning)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
