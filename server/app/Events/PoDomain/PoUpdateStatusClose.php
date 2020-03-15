<?php
namespace App\Events\PoDomain;

use App\Events\PoDomain\Context\PoUpdateStatusCloseContext;
use Illuminate\Queue\SerializesModels;

class PoUpdateStatusClose {
    use SerializesModels;

    public $poUpdateStatusCloseContext;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(PoUpdateStatusCloseContext $poUpdateStatusCloseContext)
    {
        $this->poUpdateStatusCloseContext = $poUpdateStatusCloseContext;
    }
}
