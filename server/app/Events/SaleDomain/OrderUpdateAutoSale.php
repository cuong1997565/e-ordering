<?php
namespace App\Events\SaleDomain;

use App\Events\SaleDomain\Context\OrderUpdateAutoSaleContext;
use Illuminate\Queue\SerializesModels;

class OrderUpdateAutoSale {
    use SerializesModels;

    public $orderUpdateAutoSale;
    /**
     * Create a new event instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct(OrderUpdateAutoSaleContext $orderUpdateAutoSale)
    {
        $this->orderUpdateAutoSale = $orderUpdateAutoSale;
    }
}
