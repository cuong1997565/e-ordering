<?php

namespace App\Console\Commands;

use App\Dashboard\Handlers\DashboardViewModelGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckDashBoardPOandSODate extends Command
{

    protected $name = 'command:CheckDashBoardPOandSODate';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DashboardViewModelGenerator $dashboardViewModelGenerator)
    {
        $now = Carbon::now();
        $po_expired = DB::table('orders')->where('deliver_date', '<', $now)->get();
        $count_po_expired = count($po_expired);
        $dashboardViewModelGenerator->onPoExpired($po_expired, $count_po_expired);
        $so_expired = DB::table('sale_orders')->where('so_date', '<', $now)->get();
        $count_so_expired = count($so_expired);
        $dashboardViewModelGenerator->onSoExpired($po_expired, $count_so_expired);
    }
}
