<?php
namespace App\DeliveryNote\Events;

use Illuminate\Queue\SerializesModels;

class DeliveryNoteConfirmedFromApprovedForDashboard {
    use SerializesModels;

    public $deliveryNoteId;

    public $distributorId;

    public $factoryId;

    public $amount;

    public $salePersonId;

    public function __construct($deliveryNoteId, $distributorId, $factoryId, $amount, $salePersonId)
    {
        $this->deliveryNoteId = $deliveryNoteId;
        $this->distributorId = $distributorId;
        $this->factoryId = $factoryId;
        $this->amount = $amount;
        $this->salePersonId = $salePersonId;
    }
}
