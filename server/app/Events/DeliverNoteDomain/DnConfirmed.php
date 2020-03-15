<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnConfirmedContext;
use Illuminate\Queue\SerializesModels;

class DnConfirmed {
    use SerializesModels;

    public $dnConfirmed;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnConfirmedContext $dnConfirmed)
    {
        $this->dnConfirmed = $dnConfirmed;
    }
}
