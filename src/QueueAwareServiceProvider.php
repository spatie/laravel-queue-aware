<?php

namespace Spatie\QueueAware;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QueueAwareServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-queue-aware');
    }

    public function registeringPackage(): void
    {
        $this->app->bind('laravel-queue-aware', QueueAware::class);
    }

    public function bootingPackage()
    {
        QueueAwareFacade::boot();
    }
}
