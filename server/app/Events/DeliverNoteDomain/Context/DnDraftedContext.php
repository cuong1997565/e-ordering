<?php
namespace App\Events\DeliverNoteDomain\Context;

class DnDraftedContext {
    public $deliveryNote;

    public function __construct($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
    }
}
