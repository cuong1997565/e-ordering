<?php
namespace App\Events\PoDomain\Context;

class PoUpdateStatusCloseContext {
    public $payload = [];

    // Example payload:
    /**
     * Example payload:
     *
     *    0  => [
     *         'order_id' => 12,
     *          'note_close' : 'qweq qweqw eqwe'
     *    ]
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }
}
