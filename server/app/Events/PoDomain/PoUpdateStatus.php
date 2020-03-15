<?php
namespace App\Events\PoDomain;

use App\Events\PoDomain\Context\PoUpdateStatusContext;
use Illuminate\Queue\SerializesModels;

class PoUpdateStatus {
    use SerializesModels;

    public $poUpdateStatusContext;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(PoUpdateStatusContext $poUpdateStatusContext)
    {
        $this->poUpdateStatusContext = $poUpdateStatusContext;
    }
}
