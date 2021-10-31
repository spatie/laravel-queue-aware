<?php

namespace Spatie\QueueAware\Contracts;

interface MakesQueueAware
{
    public function getContainerKey(): string;

    public function shouldBeAware(object $queueable): bool;

    public function dehydrate(): mixed;

    public function hydrate($data): mixed;
}
