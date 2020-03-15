<?php
namespace App\Events\CreditAccountDomain;

use App\Events\CreditAccountDomain\Context\CreditAccountUpdateAmountContext;
use Illuminate\Queue\SerializesModels;

class CreditAccountUpdateAmount {
    use SerializesModels;

    public $accountUpdateAmountContext;
    /**
     * Create a new event instance.
     *
     * @param  \App\CreditAccount  $creditAccount
     * @return void
     */
    public function __construct(CreditAccountUpdateAmountContext $accountUpdateAmountContext)
    {
        $this->accountUpdateAmountContext = $accountUpdateAmountContext;
    }
}
