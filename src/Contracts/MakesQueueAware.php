<?php

namespace Spatie\QueueAware\Contracts;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobRetryRequested;

interface MakesQueueAware
{
    /**
     * Retrieve the binding that is used in the container to reference this singleton.
     */
    public function getContainerKey(): string;

    /**
     * Whether the queue should be aware of the singleton.
     *
     * @param  object  $queueable The queueable instance that would be made aware of the singleton.
     */
    public function shouldBeAware(object $queueable): bool;

    /**
     * Return data that can be used to rebuild this singleton later.
     */
    public function dehydrate(): mixed;

    /**
     * Create a new instance of the singleton to be bound in the container.
     *
     * @param mixed $data The data returned for this singleton from the dehydrate method.
     * @param JobProcessing|JobRetryRequested $event The event that triggered the hydrate method.
     * @return void|mixed Either the new instance or void if the singleton was bound manually in this method.
     */
    public function hydrate($data, $event): mixed;
}
