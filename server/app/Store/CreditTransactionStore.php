<?php

namespace App\Store;

use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use App\Models\Error;
use App\Events\CreditAccountDomain\Context\CreditAccountUpdateAmountContext;
use App\Events\CreditAccountDomain\CreditAccountUpdateAmount;
use Illuminate\Support\Facades\DB;

class CreditTransactionStore
{
    public function save(CreditTransaction $creditTransaction) {
        if ($creditTransaction->id !== null && $creditTransaction->id !== 0) {
            return Error::NewAppError('store.transaction_store.save.existing.app_error', 'CreditAccountStore.saveCreditTransaction', null, "id={$creditTransaction->id}", StatusBadRequest);
        }

        $creditTransaction->id = null;

        try {
            if (!CreditAccount::find($creditTransaction->credit_id)) {
                return Error::NewAppError('store.store.credit_acount.update.find.app_error',
                    'CreditAccountStore.saveCreditTransaction', null,
                    "id=" . $creditTransaction->credit_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.store.credit_acount.update.find.app_error',
                'CreditAccountStore.saveCreditTransaction', null,
                "id=" . $creditTransaction->credit_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $creditTransaction->toInstanceArray()) {
                return Error::NewAppError('store.transaction_store.save.app_error', 'CreditAccountStore.saveCreditTransaction', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.transaction_store.save.app_error', 'Credit_AccountStore.saveCreditTransaction', null, 'cannot convert instance to array ', StatusInternalServerError);
        }
        DB::beginTransaction();
        try {
            $credit_transaction = CreditTransaction::create($data);
            $updateAmountCreditAccount = new CreditAccountUpdateAmountContext($credit_transaction->credit_id
                , $credit_transaction->amount, $credit_transaction->transaction_type);

            $results = event(new CreditAccountUpdateAmount($updateAmountCreditAccount));

            foreach ($results as $result) {
                if (is_object($result) && get_class($result) == Error::class) {
                    return $result;
                    DB::rollBack();
                }
            }
        DB::commit();
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.save.app_error', 'Credit_AccountStore.save', null, $e->getMessage(), StatusInternalServerError);
        }

        return $credit_transaction;
    }

}
