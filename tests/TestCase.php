<?php

namespace Spatie\QueueAware\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use PHPUnit\Framework\Assert;
use Spatie\LaravelRay\RayServiceProvider;
use Spatie\QueueAware\QueueAwareServiceProvider;
use Throwable;

class TestCase extends Orchestra
{
    public function runJobs(): void
    {
        $this->artisan('queue:work', [
            '--stop-when-empty' => true,
            '--sleep' => 0,
        ]);
    }

    /**
     * @param  class-string  $job
     */
    public function assertJobSucceeded(string $job): self
    {
        $success = $this->app
            ->make('cache')
            ->pull('job-result-'.class_basename($job));

        throw_if($success instanceof Throwable, $success);

        Assert::assertTrue($success);

        return $this;
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            RayServiceProvider::class,
            QueueAwareServiceProvider::class,
        ];
    }
}
