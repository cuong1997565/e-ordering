<?php
namespace App\Events\CreditAccountDomain\Context;

class CreditAccountUpdateAmountContext {

    public $id_credit_acount;


    public $amount;

    public $type = null;

    public function __construct($id_credit_acount, $amount, $type)
    {
        $this->id_credit_acount = $id_credit_acount;
        $this->type = $type;
        $this->amount = $amount;
    }
}
