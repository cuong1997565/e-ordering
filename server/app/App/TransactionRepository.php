<?php

namespace App\App;

use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use App\Models\Distributor;
use App\Models\Error;
use App\Store\DeliveryNoteStore;
use App\Store\SpecificationStore;
use App\Store\TransactionStore;

class TransactionRepository
{
    public function checkIsLimitOverForDistributorId($distributorId, $amount) {
        $distributor = (new DistributorRepository())->getDistributor($distributorId);

        if (is_object($distributor) && get_class($distributor) == Error::class) {
            return $distributor;
        }

        if (!$creditAccount = $distributor->credit_account) {
            $error = Error::NewAppError('store.transaction_store.finding.credit_account.not_found', 'TransactionStore.checkLimitForDistributorById', [], null, StatusBadRequest);
            return $error;
        }

        return  $amount > $creditAccount->credit_limit;
    }

    public function createTransactionDr(CreditTransaction $transaction)
    {
        $result = (new TransactionStore())->save($transaction);

        return $result;
    }

    public function createTransactionReverse(CreditTransaction $transaction)
    {
        $result = (new TransactionStore())->save($transaction);

        return $result;
    }

    public function updateCreditAccountTypeDr(CreditAccount $creditAccount, $amount) {
        $result = (new TransactionStore())->updateCreditAccountTypeDr($creditAccount, $amount);

        return $result;
    }
    /*
     * where dk approved confirmed không cần check vượt quá hạn mức : credit limit
     *
    * */
    public function updateCreditAccountTypeDrWhereApprovedConfirmed(CreditAccount $creditAccount, $amount) {
        $result = (new TransactionStore())->updateCreditAccountTypeDrWhereApprovedConfirmed($creditAccount, $amount);

        return $result;
    }

    public function updateCreditAccountWhenReverse(CreditAccount $creditAccount, $amount) {
        $result = (new TransactionStore())->updateCreditAccountWhenReverse($creditAccount, $amount);

        return $result;
    }

    public function getCreditAccount($distributorId) {
        return (new TransactionStore())->get($distributorId);
    }
}
