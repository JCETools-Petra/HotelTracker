<<<<<<< HEAD
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
=======
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

<<<<<<< HEAD
// === AWAL PERBAIKAN ===
// Import Event dan Listener dari lokasi yang benar
use App\Events\OccupancyUpdated;
use App\Listeners\SendOccupancyUpdateNotification; // Untuk Email
use App\Listeners\SendOccupancyUpdateWhatsApp;    // Untuk WhatsApp
// === AKHIR PERBAIKAN ===

=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
<<<<<<< HEAD

        // Pastikan blok ini terlihat seperti ini
        OccupancyUpdated::class => [
            //SendOccupancyUpdateNotification::class,
            SendOccupancyUpdateWhatsApp::class,
        ],
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
>>>>>>> origin/master
}