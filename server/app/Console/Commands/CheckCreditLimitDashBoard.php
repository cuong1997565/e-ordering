<?php

namespace App\Console\Commands;

use App\Dashboard\Handlers\DashboardViewModelGenerator;
use App\Models\CreditAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckCreditLimitDashBoard extends Command
{
    protected $name = 'command:CheckCreditLimitDashBoard';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DashboardViewModelGenerator $dashboardViewModelGenerator)
    {
//        $credit_limit_expired = DB::table('credit_accounts')->where('credit_limit', '<=', 0)->get();
        $credit_limit_expired = CreditAccount::with('distributor')->where('credit_limit', '<=', 0)->get();
//        $credit_limit_upcoming_expired = DB::table('credit_accounts')->whereBetween('credit_limit', [1, 1000000])->get();
        $credit_limit_upcoming_expired = CreditAccount::with('distributor')->whereBetween('credit_limit', [1, 1000000])->get();
        $dashboardViewModelGenerator->onCreditLimitExpired($credit_limit_expired);
        $dashboardViewModelGenerator->onCreditLimitUpcomingExpired($credit_limit_upcoming_expired);
    }
}
