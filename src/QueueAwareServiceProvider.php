<?php

namespace Spatie\QueueAware;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\QueueAware\Commands\QueueAwareCommand;

class QueueAwareServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-queue-aware')
            ->hasConfigFile();
    }
}
