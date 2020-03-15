<?php
namespace App\Events\DeliverNoteDomain\Context;

class DnReversedContext {
    public $payload = [];

    // Example transaction:
    /**
     * left so_item_id, right: delivered_quantity
     * Example payload:
     *    [
     *         'amount' => 100,
     *         'dn_id' => 1,
     *         'credit_id' => 1|null
     *    ]
     */
    public $transaction = [];

    public $type = null;
    // Example payload:
    /**
     * left so_item_id, right: delivered_quantity
     * Example payload:
     *    [
     *         1 => 2.00,   // left so_item_id, right: delivered_quantity
     *         2 => 3.00
     *    ]
     */
    public function __construct($payload, $type, $transaction)
    {
        $this->payload = $payload;
        $this->type = $type;
        $this->transaction = $transaction;
    }
}
