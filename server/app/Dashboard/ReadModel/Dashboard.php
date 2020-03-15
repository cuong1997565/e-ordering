<?php
namespace App\Dashboard\ReadModel;

class Dashboard {
    // po
    public static $PoSubmitedSummary = 'Po_Submit';
    public static $PoAcceptedSummary = 'Po_Accept';
    public static $PoExpiredSummary = 'Po_Expired';
    public static $PoClosedSummany = 'Po_Closed';
    // end po

    // so
    public static $SoDraftedSummary = 'So_Draft';
    public static $SoExpiredSummary = 'So_Expired';
    public static $SoConfirmedSummary = 'So_Confirmed';
    public static $SoClosedSummany = 'So_Closed';
    // end so

    // dn
    public static $deliveryNoteDraftSummary = 'Dn_Draft';
    public static $deliveryNoteWaitingForConfirmSummary = 'Dn_WaitingForConfirm';
    public static $deliveryNoteApprovedSummary = 'Dn_Approve';
    public static $deliveryNoteConfirmedSummary = 'Dn_Confirm';
    // end dn

    // credit limit list
    public static $creditlimitListExpiredSummary = 'cl_expired';
    public static $creditlimitListUpcomingExpiredSummary = 'cl_upcoming_expired';
    // end credit limit list
    public $id;
    public $value;
    public $type;
    public $items = [];
    public function __construct($id, $value, $type, $items)
    {
        $this->id = $id;
        $this->value = $value;
        $this->type = $type;
        $this->items = $items;
    }


    public function id() {
        return $this->id;
    }

    public function value() {
        return $this->value;
    }

    public function type() {
        return $this->type;
    }

    public function items() {
        return $this->items;
    }
}
