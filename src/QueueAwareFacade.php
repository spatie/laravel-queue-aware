<?php

declare(strict_types=1);

namespace Spatie\QueueAware;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void register($class, Closure $dehydrate = null, Closure $hydrate = null)
 *
 * @see \Spatie\QueueAware\QueueAware
 */
final class QueueAwareFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-queue-aware';
    }
}
