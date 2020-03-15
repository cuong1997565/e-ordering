<?php
namespace App\Events\SaleDomain;

use App\Events\SaleDomain\Context\SoConfirmedContext;
use Illuminate\Queue\SerializesModels;

class SoConfirmed {
    use SerializesModels;

    public $soConfirmed;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(SoConfirmedContext $soConfirmed)
    {
        $this->soConfirmed = $soConfirmed;
    }
}
