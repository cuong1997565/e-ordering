<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnApprovedContext;
use Illuminate\Queue\SerializesModels;

class DnApproved {
    use SerializesModels;

    public $dnApproved;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnApprovedContext $dnApproved)
    {
        $this->dnApproved = $dnApproved;
    }
}
