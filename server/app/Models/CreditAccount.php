<?php

namespace App\Models;

use App\App\CreditAccountRepository;

class CreditAccount extends AppModel
{
    protected $table = 'credit_accounts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'distributor_id', 'amount', 'hold_amount', 'available_amount', 'credit_limit'
    ];

    public $belongTo =
        [
            'distributor',
        ];

    public function createCreditAccount()
    {
        $this->id = null;

        $result = (new CreditAccountRepository())->createCreditAccount($this);

        return $result;
    }

    public function updateCreditAccount()
    {
        $result = (new CreditAccountRepository())->updateCreditAccount($this);

        return $result;
    }

    public function credit_transactions()
    {
        return $this->hasMany(CreditTransaction::class, 'credit_id');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function isValid()
    {
        return true;
    }
}
