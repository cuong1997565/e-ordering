<?php
namespace App\Events\PoDomain;

use App\Events\PoDomain\Context\PoCreateAutoContext;
use Illuminate\Queue\SerializesModels;

class PoCreateAuto {
    use SerializesModels;

    public $poCreateAuto;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(PoCreateAutoContext $poCreateAuto)
    {
        $this->poCreateAuto = $poCreateAuto;
    }
}
