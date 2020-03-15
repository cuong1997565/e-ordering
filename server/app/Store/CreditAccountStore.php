<?php

namespace App\Store;

use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use App\Models\Error;
use Illuminate\Support\Facades\DB;

class CreditAccountStore
{

    public function save(CreditAccount $credit_account)
    {
        if ($credit_account->id !== null && $credit_account->id !== 0) {
            return Error::NewAppError('store.credit_account.save.existing.app_error', 'CreditAccountStore.save', null, "id={$credit_account->id}", StatusBadRequest);
        }

        $credit_account->id = null;

        try {
            if (!$data = $credit_account->toInstanceArray()) {
                return Error::NewAppError('store.credit_account.save.app_error', 'CreditAccountStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.save.app_error', 'Credit_AccountStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $credit_account = CreditAccount::create($data);
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.save.app_error', 'Credit_AccountStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $credit_account;
    }

    public function update(CreditAccount $credit_account)
    {
        if (!$credit_account->id || !is_integer($credit_account->id)) {
            return Error::NewAppError('model.credit_account.is_valid.id.app_error', 'CreditAccountStore.update', null, "id={$credit_account->id}", StatusBadRequest);
        }

        try {
            if (!CreditAccount::find($credit_account->id)) {
                return Error::NewAppError('store.credit_account.update.find.app_error', 'CreditAccountStore.update', null, "id=" . $credit_account->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.update.finding.app_error', 'CreditAccountStore.update', null, "id=" . $credit_account->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $credit_account->toInstanceArray()) {
                return Error::NewAppError('store.credit_account.save.app_error', 'CreditAccountStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.update.app_error', 'CreditAccountStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            CreditAccount::where('id', $credit_account->id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.update.updating.app_error', 'CreditAccountStore.update', null, "id=" . $credit_account->id . $e->getMessage(), StatusInternalServerError);
        }

        return $credit_account;
    }

    public function updateCreditAccountDistributor($creditAccount) {
//        if (!$creditAccount->id || !is_integer($creditAccount->id)) {
//            return Error::NewAppError('model.credit_account.is_valid.id.app_error', 'CreditAccountStore.updateCreditAccountDistributor',
//                null, "id={$creditAccount->id}", StatusBadRequest);
//        }

        try {
            if ($credit = !CreditAccount::where('distributor_id', $creditAccount->distributor_id)->get()) {
                return Error::NewAppError('store.credit_account.update.find.app_error', 'CreditAccountStore.updateCreditAccountDistributor', null, "distributor_id=" . $creditAccount->distributor_id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.update.finding.app_error', 'CreditAccountStore.updateCreditAccountDistributor', null, "distributor_id=" . $creditAccount->distributor_id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $creditAccount->toInstanceArray()) {
                return Error::NewAppError('store.credit_account.save.app_error', 'CreditAccountStore.updateCreditAccountDistributor', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.update.app_error', 'CreditAccountStore.updateCreditAccountDistributor', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        unset($data['id']);

        try {

            if (isset($data['id'])) {
                unset($data['id']);
            }
            CreditAccount::where('distributor_id', $creditAccount->distributor_id)
                ->update($data);

        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.update.updating.app_error', 'CreditAccountStore.update', null, "id=" . $creditAccount->distributor_id . $e->getMessage(), StatusInternalServerError);
        }

        return $creditAccount;
    }


    public function findCreditAccount($distributor_id) {
        try {
            $creditAccount = CreditAccount::where('distributor_id', $distributor_id)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.get_credit_account_by_id.app_error',
                'CreditAccountStore.findCreditAccount', null, $e->getMessage(), StatusInternalServerError);
        }

        if (!$creditAccount) {
            return Error::NewAppError('store.credit_account.get_credit_account_by_id.app_error',
                'CreditAccountStore.findCreditAccount', null, '', StatusInternalServerError);
        }
        return $creditAccount;
    }

    public function getCreditAccountById($id) {
        try {
            $creditAccount = CreditAccount::where('id', $id)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.credit_account.get_credit_account_by_id.app_error',
                'CreditAccountStore.getCreditAccountById', null, $e->getMessage(), StatusInternalServerError);
        }

        if (!$creditAccount) {
            return Error::NewAppError('store.credit_account.get_credit_account_by_id.app_error',
                'CreditAccountStore.getCreditAccountById', null, '', StatusInternalServerError);
        }
        return $creditAccount;
    }

    public function updateCreditAccountAmount($id, $amount, $type) {
        DB::beginTransaction();
        try {
            $item = $this->getCreditAccountById($id);
            if (is_object($item) && get_class($item) == Error::class) {
                return Error::NewAppError('store.credit_account.get_credit_account_by_id.app_error', 'CreditAccountStore.updateCreditAccountAmount', null, '', StatusBadRequest);
            }

            if(CreditTransaction::$transactionTypeDR == $type) {
                $available_amount = $item->available_amount - $amount;
                if($available_amount < $item->credit_limit) {
                    return Error::NewAppError('store.credit_account.available_amount_less_credit_limit.app_error',
                        'CreditAccountStore.updateCreditAccountAmount', null, '', StatusBadRequest);

                }

                $itemamount = $item->amount - $amount;

                if($itemamount < $item->credit_limit) {
                    return Error::NewAppError('store.credit_account.amount_less_credit_limit.app_error',
                        'CreditAccountStore.updateCreditAccountAmount', null, '', StatusBadRequest);
                }

                $item->available_amount = $available_amount;
                $item->amount = $itemamount;
                $item->update([
                    'available_amount' => $available_amount,
                    'amount' => $itemamount
                ]);
            }

            if(CreditTransaction::$transactionTypeCR == $type) {
                $available_amount = $item->available_amount +  $amount;
                $itemamount = $item->amount + $amount;
                $item->available_amount = $available_amount;
                $item->amount = $itemamount;
                $item->update([
                    'available_amount' => $available_amount,
                    'amount' => $itemamount
                ]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.credit_account.update_amount_false.app_error',
                'CreditAccountStore.updateCreditAccountAmount', null, $e->getMessage(), StatusInternalServerError);
        }
    }
}
