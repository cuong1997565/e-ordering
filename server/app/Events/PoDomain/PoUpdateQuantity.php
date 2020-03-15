<?php
namespace App\Events\PoDomain;

use App\Events\PoDomain\Context\PoUpdateQuantityContext;
use Illuminate\Queue\SerializesModels;

class PoUpdateQuantity {
    use SerializesModels;

    public $poUpdateContext;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(PoUpdateQuantityContext $poUpdateContext)
    {
        $this->poUpdateContext = $poUpdateContext;
    }
}
