<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnConfirmAfterApprovedContext;
use Illuminate\Queue\SerializesModels;

class DnConfirmAfterApproved {
    use SerializesModels;

    public $dnConfirmAfterApproved;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnConfirmAfterApprovedContext $dnConfirmAfterApproved)
    {
        $this->dnConfirmAfterApproved = $dnConfirmAfterApproved;
    }
}
