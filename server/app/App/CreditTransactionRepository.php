<?php

namespace App\App;

use App\Store\CreditTransactionStore;

class CreditTransactionRepository
{

    public function createCreditTransaction($credit_transaction) {
        $result = (new CreditTransactionStore())->save($credit_transaction);
        return $result;
    }

}
