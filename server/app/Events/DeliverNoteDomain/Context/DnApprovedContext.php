<?php
namespace App\Events\DeliverNoteDomain\Context;

class DnApprovedContext {
    public $deliveryNote;

    public function __construct($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }
}
