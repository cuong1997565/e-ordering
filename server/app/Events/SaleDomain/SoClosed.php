<?php
namespace App\Events\SaleDomain;

use App\Events\SaleDomain\Context\SoClosedContext;
use Illuminate\Queue\SerializesModels;

class SoClosed {
    use SerializesModels;

    public $soClosed;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(SoClosedContext $soClosed)
    {
        $this->soClosed = $soClosed;
    }
}
