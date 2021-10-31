<?php

declare(strict_types=1);

namespace Spatie\QueueAware\Tests\Doubles\QueueAwareables;

use Spatie\QueueAware\Contracts\MakesQueueAware;
use Spatie\QueueAware\Tests\Doubles\Singletons\TestableSingleton;

final class MakesTestableSingletonQueueAware implements MakesQueueAware
{
    public function __construct(private bool $shouldMakeQueueAware = true)
    {
    }

    public function getContainerKey(): string
    {
        return TestableSingleton::class;
    }

    public function shouldBeAware(object $queueable): bool
    {
        return $this->shouldMakeQueueAware;
    }

    public function dehydrate(): mixed
    {
        return app(TestableSingleton::class)->identifier;
    }

    public function hydrate($data): mixed
    {
        return new TestableSingleton($data);
    }
}
