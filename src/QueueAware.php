<?php

declare(strict_types=1);

namespace Spatie\QueueAware;

use BadMethodCallException;
use Closure;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobRetryRequested;
use Spatie\QueueAware\Contracts\MakesQueueAware;
use Spatie\QueueAware\Support\AnonymousMakesQueueAware;

final class QueueAware
{
    /**
     * @var array<string, MakesQueueAware>
     */
    private array $queueAwareInstances = [];

    public function register(string|MakesQueueAware $instance, Closure $dehydrate = null, Closure $hydrate = null): void
    {
        if ($instance instanceof MakesQueueAware) {
            $this->queueAwareInstances[$instance->getContainerKey()] = $instance;

            return;
        }

        if ($dehydrate === null || $hydrate === null) {
            throw new BadMethodCallException('You must include dehydrate and hydrate closures when registering anonymous queue aware instances.');
        }

        $containerKey = is_string($instance) ? $instance : $instance::class;
        $this->queueAwareInstances[$containerKey] = new AnonymousMakesQueueAware($containerKey, $dehydrate, $hydrate);
    }

    public function boot(): void
    {
        $this->listenForJobsBeingQueued();
        $this->listenForJobsBeingProcessed();
        $this->listenForJobsRetryRequested();
    }

    private function listenForJobsBeingQueued(): void
    {
        app('queue')->createPayloadUsing(
            fn ($connectionName, $queue, $payload) => collect($this->queueAwareInstances)
            ->filter(fn (MakesQueueAware $instance) => $instance->shouldBeAware($payload['data']['command']))
            ->map(fn (MakesQueueAware $instance, string $key) => $instance->dehydrate())
            ->toArray()
        );
    }

    private function listenForJobsBeingProcessed(): void
    {
        app('events')->listen(
            JobProcessing::class,
            fn (JobProcessing $event) => $this->bindInstancesInQueue($event->job->payload(), $event)
        );
    }

    private function listenForJobsRetryRequested(): void
    {
        app('events')->listen(
            JobRetryRequested::class,
            fn (JobRetryRequested $event) => $this->bindInstancesInQueue($event->payload(), $event)
        );
    }

    private function bindInstancesInQueue(array $payload, JobProcessing|JobRetryRequested $event): void
    {
        collect($this->queueAwareInstances)
            ->filter(fn (MakesQueueAware $instance, string $key) => array_key_exists($key, $payload))
            ->map(fn (MakesQueueAware $instance, string $key) => $instance->hydrate($payload[$key], $event))
            ->filter()
            ->each(fn (mixed $instance, string $key) => app()->instance($key, $instance));
    }
}
