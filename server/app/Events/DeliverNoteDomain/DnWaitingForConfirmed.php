<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnConfirmedContext;
use App\Events\DeliverNoteDomain\Context\DnWaitingForConfirmedContext;
use Illuminate\Queue\SerializesModels;

class DnWaitingForConfirmed {
    use SerializesModels;

    public $dnWaitingForConfirmed;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnWaitingForConfirmedContext $dnWaitingForConfirmed)
    {
        $this->dnWaitingForConfirmed = $dnWaitingForConfirmed;
    }
}
