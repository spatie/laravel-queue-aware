<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Spatie\QueueAware\QueueAwareFacade;
use Spatie\QueueAware\Tests\Doubles\Jobs\AwareJob;
use Spatie\QueueAware\Tests\Doubles\QueueAwareables\ImplicitMakesTestableSingletonQueueAware;
use Spatie\QueueAware\Tests\Doubles\QueueAwareables\MakesTestableSingletonQueueAware;
use Spatie\QueueAware\Tests\Doubles\Singletons\TestableSingleton;

it('can correctly resolve a singleton in the job when explicit', function () {
    $singleton = new TestableSingleton('foobar');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(new MakesTestableSingletonQueueAware());

    $job = new AwareJob('foobar');
    $queue = $this->app->make('queue');
    $queue->push($job);

    $this->app->forgetInstance(TestableSingleton::class);

    $this->runJobs();

    $this->assertJobSucceeded(AwareJob::class);
});

it('can correctly resolve a singleton in the job when implicit', function () {
    $singleton = new TestableSingleton('foobar');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(new ImplicitMakesTestableSingletonQueueAware());

    $job = new AwareJob('foobar');
    $queue = $this->app->make('queue');
    $queue->push($job);

    $this->app->forgetInstance(TestableSingleton::class);

    $this->runJobs();

    $this->assertJobSucceeded(AwareJob::class);
});

it('will not make the queue aware if shouldBeAware returns false', function () {
    $singleton = new TestableSingleton('foobar');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(new MakesTestableSingletonQueueAware(false));

    $job = new AwareJob('foobar');
    $queue = $this->app->make('queue');
    $queue->push($job);

    $this->app->forgetInstance(TestableSingleton::class);

    $this->runJobs();

    // We'll perform this assertion to forward the exception thrown by the job
    $this->assertJobSucceeded(AwareJob::class);
})->throws(BindingResolutionException::class);
