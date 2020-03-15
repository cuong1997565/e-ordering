<?php
namespace App\Events\PoDomain\Context;

class PoUpdateQuantityContext {
    public $payload = [];

    // Example payload:
    /**
     * Example payload:
     *
     *    0  => [
     *         'product_id' => 10,
     *         'sale_quantity' => 10,
     *         'order_id' => 12
     *    ]
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}
