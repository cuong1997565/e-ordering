<?php
namespace App\Events\SaleDomain\Context;

class OrderUpdateAutoSaleContext {
    public $payload = [];

    // Example payload:
    /**
     * Example payload:
     *
     *    0  => [
     *         'order_id' => 12,
     *          'sale_id' => 12
     *    ]
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}
