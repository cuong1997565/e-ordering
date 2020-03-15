<?php
namespace App\Events\PoDomain;

use App\Events\PoDomain\Context\PoAcceptedContext;
use Illuminate\Queue\SerializesModels;

class PoAccepted {
    use SerializesModels;

    public $poAccept;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(PoAcceptedContext $poAccept)
    {
        $this->poAccept = $poAccept;
    }
}
