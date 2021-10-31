<?php

use Spatie\QueueAware\QueueAwareFacade;
use Spatie\QueueAware\Tests\Doubles\Jobs\AwareJob;
use Spatie\QueueAware\Tests\Doubles\Singletons\TestableSingleton;

it('can correctly resolve a singleton in the job with closure syntax when binding is explicit', function () {
    $singleton = new TestableSingleton('foobar');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(
        $singleton::class,
        fn (TestableSingleton $instance) => $instance->identifier,
        fn (string $identifier) => new TestableSingleton($identifier),
    );

    $job = new AwareJob('foobar');
    $queue = $this->app->make('queue');
    $queue->push($job);

    $this->app->forgetInstance(TestableSingleton::class);

    $this->runJobs();

    $this->assertJobSucceeded(AwareJob::class);
});

it('can correctly resolve a singleton in the job with closure syntax when binding is implicit', function () {
    $singleton = new TestableSingleton('Luke Downing');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(
        $singleton::class,
        fn (TestableSingleton $instance) => $instance->identifier,
        function (string $identifier) {
            app()->instance(TestableSingleton::class, new TestableSingleton($identifier));
        },
    );

    $job = new AwareJob('Luke Downing');
    $queue = $this->app->make('queue');
    $queue->push($job);

    $this->app->forgetInstance(TestableSingleton::class);

    $this->runJobs();

    $this->assertJobSucceeded(AwareJob::class);
});

it('throws an exception if registered anonymously without a dehydrator', function () {
    $singleton = new TestableSingleton('Luke Downing');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(
        $singleton::class,
        null,
        fn (string $identifier) => new TestableSingleton($identifier),
    );
})->throws(BadMethodCallException::class);

it('throws an exception if registered anonymously without a hydrator', function () {
    $singleton = new TestableSingleton('Luke Downing');
    $this->app->instance(TestableSingleton::class, $singleton);

    QueueAwareFacade::register(
        $singleton::class,
        fn (TestableSingleton $instance) => $instance->identifier,
        null,
    );
})->throws(BadMethodCallException::class);
