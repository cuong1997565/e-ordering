<?php
namespace App\Events\PoDomain;

use App\Events\PoDomain\Context\PoSubmitedContext;
use Illuminate\Queue\SerializesModels;

class PoSubmited {
    use SerializesModels;

    public $poSubmited;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(PoSubmitedContext $poSubmited)
    {
        $this->poSubmited = $poSubmited;
    }
}
