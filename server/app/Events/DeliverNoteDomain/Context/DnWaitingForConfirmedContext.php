<?php
namespace App\Events\DeliverNoteDomain\Context;

class DnWaitingForConfirmedContext {
    public $deliveryNote;

    public function __construct($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }
}
