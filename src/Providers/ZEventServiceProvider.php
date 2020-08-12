<?php

namespace Zijinghua\Zfilesystem\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Zijinghua\Zfilesystem\Events\Api\SaveDataEvent;
use Zijinghua\Zfilesystem\Events\Api\UploadEvent;
use Zijinghua\Zfilesystem\Listeners\Api\SaveDataListener;
use Zijinghua\Zfilesystem\Listeners\Api\UploadListener;

class ZEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UploadEvent::class =>[
            UploadListener::class
        ],
        SaveDataEvent::class =>[
            SaveDataListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
