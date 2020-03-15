<?php

namespace App\App;

use App\Store\CreditAccountStore;

class CreditAccountRepository
{
    public function createCreditAccount($credit_account)
    {
        $result = (new CreditAccountStore())->save($credit_account);

        return $result;
    }

    public function updateCreditAccount($credit_account)
    {
        $result = (new CreditAccountStore())->update($credit_account);

        return $result;
    }

    /*
     * update where distributor_id
     * */
    public function updateCreditAccountDistributor($credit_account) {
        $result = (new CreditAccountStore())->updateCreditAccountDistributor($credit_account);

        return $result;
    }

    /*
     * $id : account 1
     * $$amount : 1000
     * $type : DR/CR
     * */
    public function updateCreditAccountAmount($id, $amount, $type) {
        return (new CreditAccountStore())->updateCreditAccountAmount($id, $amount, $type);
    }

    /*
     * find ditributor_id about table credit account
     * */
    public function findCreditAccount($id) {
        return (new CreditAccountStore())->findCreditAccount($id);
    }
}
