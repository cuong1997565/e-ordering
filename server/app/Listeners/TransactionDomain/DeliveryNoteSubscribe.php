<?php
namespace App\Listeners\TransactionDomain;

use App\App\SaleOrderRepository;
use App\App\TransactionRepository;
use App\Events\DeliverNoteDomain\DnConfirmed;
use App\Events\DeliverNoteDomain\DnApprovedConfirmed;
use App\Events\DeliverNoteDomain\DnReversed;
use App\Models\CreditTransaction;
use App\Models\Error;

class DeliveryNoteSubscribe {
    public function onDeliverNoteConfirm(DnConfirmed $event) {
        $transactionEvent = $event->dnConfirmed->transaction;

        $transaction = CreditTransaction::makeAutoTransactionDr(
            $transactionEvent['amount'], $transactionEvent['reference'],
            $transactionEvent['credit_id'], $transactionEvent['description']);


        $result = (new TransactionRepository())->createTransactionDr($transaction);
        if (is_object($result) && get_class($result) == Error::class) {
            return $result;
        }

        $creditAccount = (new TransactionRepository())->getCreditAccount($transactionEvent['distributor_id']);

        $charge = (new TransactionRepository())->updateCreditAccountTypeDr($creditAccount, $transactionEvent['amount']);

        return $charge;
    }

    public function onDeliverNoteApprovedConfirmed(DnApprovedConfirmed $event) {
        $transactionEvent = $event->dnApprovedConfirmed->transaction;
        $transaction = CreditTransaction::makeAutoTransactionDr(
            $transactionEvent['amount'], $transactionEvent['reference'],
            $transactionEvent['credit_id'], $transactionEvent['description']);


        $result = (new TransactionRepository())->createTransactionDr($transaction);

        if (is_object($result) && get_class($result) == Error::class) {
            return $result;
        }

        $creditAccount = (new TransactionRepository())->getCreditAccount($transactionEvent['distributor_id']);
        /*
         * where dk approved confirmed không cần check vượt quá hạn mức : credit limit
         *
         * */
        $charge = (new TransactionRepository())->updateCreditAccountTypeDrWhereApprovedConfirmed($creditAccount, $transactionEvent['amount']);

        return $charge;
    }

    public function onDeliverNoteReverse(DnReversed $event) {
        $transactionEvent = $event->dnReversed->transaction;

        $transaction = CreditTransaction::makeAutoTransactionCr(
            $transactionEvent['amount'], $transactionEvent['reference'],
            $transactionEvent['credit_id'], $transactionEvent['description']);

        $result = (new TransactionRepository())->createTransactionReverse($transaction);

        if (is_object($result) && get_class($result) == Error::class) {
            return $result;
        }


        $creditAccount = (new TransactionRepository())->getCreditAccount($transactionEvent['distributor_id']);

        $charge = (new TransactionRepository())->updateCreditAccountWhenReverse($creditAccount, $transactionEvent['amount']);

        return $charge;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\DeliverNoteDomain\DnConfirmed',
            'App\Listeners\TransactionDomain\DeliveryNoteSubscribe@onDeliverNoteConfirm'
        );

        $events->listen(
            'App\Events\DeliverNoteDomain\DnReversed',
            'App\Listeners\TransactionDomain\DeliveryNoteSubscribe@onDeliverNoteReverse'
        );

        $events->listen(
            'App\Events\DeliverNoteDomain\DnApprovedConfirmed',
            'App\Listeners\TransactionDomain\DeliveryNoteSubscribe@onDeliverNoteApprovedConfirmed'
        );
    }
}
