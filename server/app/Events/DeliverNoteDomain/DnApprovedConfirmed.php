<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnApprovedConfirmedContext;
use Illuminate\Queue\SerializesModels;

class DnApprovedConfirmed {
    use SerializesModels;

    public $dnApprovedConfirmed;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnApprovedConfirmedContext $dnApprovedConfirmed)
    {
        $this->dnApprovedConfirmed = $dnApprovedConfirmed;
    }
}
