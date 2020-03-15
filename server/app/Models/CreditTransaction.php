<?php

namespace App\Models;

use App\App\CreditTransactionRepository;

class CreditTransaction extends AppModel
{
    protected $table = 'credit_transactions';

    public static $isNotManual = 0;
    public static $isManual = 1;
    public static $isNotHold = 0;
    public static $isHold = 1;
    public static $transactionTypeDR = 'dr';
    public static $transactionTypeCR = 'cr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'credit_id', 'transaction_type', 'is_manual', 'is_hold', 'amount', 'reference', 'description'
    ];

//    public $belongTo =
//        [
//            'distributor'
//        ];

    public function isValid() {
        return true;
    }

    public function createCreditTransaction() {
        $this->id = null;

        $result = (new CreditTransactionRepository())->createCreditTransaction($this);

        return $result;
    }

    public function creditAccount() {
        return $this->belongsTo(CreditAccount::class,'credit_id','id');
    }

    public function distributor() {
        return $this->belongsTo(Distributor::class,'distributor_id','id');
    }

    public static function makeTransactionDr($amount, $reference, $creditId = null, $description = null) {
        $transaction = new CreditTransaction();
        $transaction->fill([
            'amount' => $amount,
            'reference' => $reference,
            'credit_id' => $creditId,
            'description' => $description
        ]);
    }
    public static function makeAutoTransactionDr($amount, $reference, $creditId = null, $description = null) {
        $transaction = new CreditTransaction();
        $transaction->fill([
            'amount' => $amount,
            'reference' => $reference,
            'credit_id' => $creditId,
            'description' => $description,
            'is_manual' => self::$isNotManual,
            'is_hold' => self::$isNotHold,
            'transaction_type' => TRANSACTION_TYPE_DR
        ]);
        return $transaction;
    }

    public static function makeAutoTransactionCr($amount, $reference, $creditId = null, $description = null) {
        $transaction = new CreditTransaction();
        $transaction->fill([
            'amount' => $amount,
            'reference' => $reference,
            'credit_id' => $creditId,
            'description' => $description,
            'is_manual' => self::$isNotManual,
            'is_hold' => self::$isNotHold,
            'transaction_type' => TRANSACTION_TYPE_CR
        ]);
        return $transaction;
    }
}
