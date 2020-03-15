<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnReversedContext;
use Illuminate\Queue\SerializesModels;

class DnReversed {
    use SerializesModels;

    public $dnReversed;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnReversedContext $dnReversed)
    {
        $this->dnReversed = $dnReversed;
    }
}
