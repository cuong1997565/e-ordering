<?php
namespace App\Events\DeliverNoteDomain\Context;

class DnConfirmAfterApprovedContext {
    public $deliveryNote;

    public function __construct($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }
}
