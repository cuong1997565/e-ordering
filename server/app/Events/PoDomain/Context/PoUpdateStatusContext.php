<?php
namespace App\Events\PoDomain\Context;

class PoUpdateStatusContext {
    public $payload = [];

    // Example payload:
    /**
     * Example payload:
     *
     *    0  => [
     *         'order_id' => 12
     *    ]
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}
