<?php

namespace Spatie\QueueAware;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\QueueAware\QueueAware
 */
class QueueAwareFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-queue-aware';
    }
}
