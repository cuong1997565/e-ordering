<?php
namespace App\Events\DeliverNoteDomain;

use App\Events\DeliverNoteDomain\Context\DnConfirmedContext;
use App\Events\DeliverNoteDomain\Context\DnDraftedContext;
use Illuminate\Queue\SerializesModels;

class DnDrafted {
    use SerializesModels;

    public $dnDrafted;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(DnDraftedContext $dnDrafted)
    {
        $this->dnDrafted = $dnDrafted;
    }
}
