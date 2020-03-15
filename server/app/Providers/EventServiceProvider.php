<?php

namespace App\Providers;

use App\Dashboard\Handlers\DashboardViewModelGenerator;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Dashboard\Handlers\DashboardViewModelGenerator',
        'App\Listeners\SaleOrderDomain\DeliveryNoteSubscribe',
        'App\Listeners\SaleOrderDomain\SaleOrderSubscribe',
        'App\Listeners\TransactionDomain\DeliveryNoteSubscribe',
        'App\Listeners\CreditAccountDomain\CreditAccountSubscribe',
        'App\Listeners\PoOrderDomain\OrderItemSubscribe',
        'App\Listeners\PoOrderDomain\OrderSubscribe',

    ];
}
