<?php
namespace App\Store;

use App\App\DistributorRepository;
use App\Events\CreditAccountDomain\Context\CreditAccountUpdateAmountContext;
use App\Events\CreditAccountDomain\CreditAccountUpdateAmount;
use App\Models\AppModel;
use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use App\Models\DeliveryNote;
use App\Models\Distributor;
use App\Models\Factory;
use App\Models\SaleOrder;
use App\Models\Store;
use App\Models\Error;
use Illuminate\Support\Facades\DB;

class TransactionStore
{
    // get by delivery id
    public function get($distributorId) {
        try {
            $creditAccount = CreditAccount::where('distributor_id', $distributorId)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.transaction_store.finding.app_error', 'TransactionStore.get', null, "distributor_id=" . $distributorId . ', ' . $e->getMessage(),StatusInternalServerError);
        }

        if($creditAccount == null)
        {
            return Error::NewAppError('store.transaction_store.finded.app_error', 'TransactionStore.get', null, "distributor_id={$distributorId}",StatusNotFound);

        }

        return $creditAccount;
    }

    public function checkLimitForCreditAccount(CreditAccount $creditAccount, $amount) {
        return $creditAccount->available_amount - $amount < $creditAccount->credit_litmit;
    }

    public function save(CreditTransaction $transaction)
    {
        return $this->upsertTransactionWithTr($transaction);
    }

    public function checkCanCredit(CreditAccount $creditAccount) {
        if ($creditAccount->available_amount < $creditAccount->credit_limit) {
            return false;
        }
        return true;
    }
    public function updateCreditAccountTypeDr(CreditAccount $creditAccount, $amount) {
        if (!is_object($creditAccount) || (is_object($creditAccount) && get_class($creditAccount) != CreditAccount::class)) {
            return Error::NewAppError('store.transaction_store.update_dr_credit_account.app_error', 'TransactionStore.updateDrCreditAccount', null, '', StatusBadRequest);
        }
        $creditAccount->amount = $creditAccount->amount - $amount;
        $creditAccount->available_amount = $creditAccount->available_amount - $amount;
        if (!$this->checkCanCredit($creditAccount)) {
            return Error::NewAppError('store.transaction_store.exceed_min_credit_account.app_error', 'TransactionStore.updateDrCreditAccount', null, '', StatusBadRequest);
        }
        return $this->upsertCreditAccountWithTr($creditAccount);
    }
    /*
     * where dk approved confirmed không cần check vượt quá hạn mức : credit limit
     *
    * */
    public function updateCreditAccountTypeDrWhereApprovedConfirmed(CreditAccount $creditAccount, $amount) {
        if (!is_object($creditAccount) || (is_object($creditAccount) && get_class($creditAccount) != CreditAccount::class)) {
            return Error::NewAppError('store.transaction_store.update_dr_credit_account.app_error', 'TransactionStore.updateDrCreditAccount', null, '', StatusBadRequest);
        }

        $creditAccount->amount = $creditAccount->amount - $amount;
        $creditAccount->available_amount = $creditAccount->available_amount - $amount;

        return $this->upsertCreditAccountWithTr($creditAccount);
    }

    public function updateCreditAccountWhenReverse(CreditAccount $creditAccount, $amount) {
        if (!is_object($creditAccount) || (is_object($creditAccount) && get_class($creditAccount) != CreditAccount::class)) {
            return Error::NewAppError('store.transaction_store.update_dr_credit_account.app_error', 'TransactionStore.updateCreditAccountWhenReverse', null, '', StatusBadRequest);
        }
        $creditAccount->amount = $creditAccount->amount + $amount;
        $creditAccount->available_amount = $creditAccount->available_amount + $amount;
        if (!$this->checkCanCredit($creditAccount)) {
            return Error::NewAppError('store.transaction_store.exceed_min_credit_account.app_error', 'TransactionStore.updateCreditAccountWhenReverse', null, '', StatusBadRequest);
        }
        return $this->upsertCreditAccountWithTr($creditAccount);
    }

    public function upsertCreditAccountWithTr(CreditAccount $creditAccount) {
        $error = $creditAccount->isValid();

        if (is_object($error) && get_class($error) == Error::class) {
            return $error;
        }
        DB::beginTransaction();
        try {
            $creditAccount = CreditAccount::updateOrCreate(
                ['id' => $creditAccount->id],
                $creditAccount->toArray()
            );
//            $transaction->save();
            DB::commit();
        } catch(\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.transaction_store.up_sert_credit_account_with_tr.app_error', 'TransactionStore.upsertCreditAccountWithTr', null, "id=" . $transaction->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        return $creditAccount;
    }

    public function upsertTransactionWithTr(CreditTransaction $transaction) {
        $error = $transaction->isValid();

        if (is_object($error) && get_class($error) == Error::class) {
            return $error;
        }
        DB::beginTransaction();
        try {
            $transaction = CreditTransaction::updateOrCreate(
                ['id' => $transaction->id],
                $transaction->toArray()
            );
            $transaction->save();

//            $updateAmountCreditAccount = new CreditAccountUpdateAmountContext($transaction->credit_id
//                , $transaction->amount, $transaction->transaction_type);
//            $results = event(new CreditAccountUpdateAmount($updateAmountCreditAccount));
//
//            foreach ($results as $result) {
//                if (is_object($result) && get_class($result) == Error::class) {
//                    return $result;
//                    DB::rollBack();
//                }
//            }
            DB::commit();
        } catch(\Exception $e) {
            // Rollback Transaction
            DB::rollBack();
            return Error::NewAppError('store.transaction_store.up_sert_credit_transaction_with_tr.app_error', 'TransactionStore.upsertTransactionWithTr', null, "id=" . $transaction->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }
        return $transaction;
    }
}
