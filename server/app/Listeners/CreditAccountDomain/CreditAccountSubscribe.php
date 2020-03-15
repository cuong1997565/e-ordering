<?php
namespace App\Listeners\CreditAccountDomain;

use App\App\CreditAccountRepository;
use App\Dashboard\ReadModel\Dashboard;
use App\Dashboard\ReadModel\Implementation\Mysql\DashboardDbContext;
use App\Dashboard\ReadModel\Implementation\Mysql\DashBoardListDbContext;
use App\Events\CreditAccountDomain\CreditAccountUpdateAmount;
use App\Events\DeliverNoteDomain\DnApprovedConfirmed;
use App\Models\Error;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class CreditAccountSubscribe {

    private $context;
    private $contextList;

    public function __construct(DashboardDbContext $context, DashBoardListDbContext $contextList)
    {
        $this->context = $context;
        $this->contextList = $contextList;
    }

    public function updateAmountCreditAccount(CreditAccountUpdateAmount $event) {
        $id_credit_acount = $event->accountUpdateAmountContext->id_credit_acount;
        $amount = $event->accountUpdateAmountContext->amount;
        $type = $event->accountUpdateAmountContext->type;

        $result = (new CreditAccountRepository())->updateCreditAccountAmount($id_credit_acount, $amount, $type);

        if (is_object($result) && get_class($result) == Error::class) {
            throw new \Exception($result->Id);
        }

    }

    public function onDnApprovedConfirmedCanNotCheckCreditLimit($event) {
        $dn_encode = json_encode($event->dnApprovedConfirmed->deliveryNote);
        DB::beginTransaction();
        try {
            $dn = $this->context->where('type', Dashboard::$deliveryNoteConfirmedSummary)->lockForUpdate()->first();
            if (!$dn){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$deliveryNoteConfirmedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $dn_encode);
            } else {
                $value = $dn->value + 1;
                $dn->value = $value;
                $dn->save();
                $this->CreateOrUpdateDashboardItems($dn->id, $dn_encode);
            }

            $dn_draft = $this->context->where('type', Dashboard::$deliveryNoteDraftSummary)->lockForUpdate()->first();
            if($dn_draft){
                if($dn_draft->value > 0) {
                    $val = $dn_draft->value - 1;
                    $dn_draft->value = $val;
                    $dn_draft->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }


    public function CreateOrUpdateDashboardItems($id, $dn_encode) {
        DB::beginTransaction();
        try {
            $dashboard_items = DB::table('dashboard_items')->where('dashboard_id', $id)->lockForUpdate()->first();
            if ($dashboard_items) {
                DB::table('dashboard_items')->where('dashboard_id', $id)->lockForUpdate()->update(['value' => $dn_encode]);
            } else {
                DB::table('dashboard_items')->insert(['value' => $dn_encode, 'dashboard_id' => $id]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\CreditAccountDomain\CreditAccountUpdateAmount',
            'App\Listeners\CreditAccountDomain\CreditAccountSubscribe@updateAmountCreditAccount'
        );

        $events->listen(
            DnApprovedConfirmed::class,
            self::class.'@'.'onDnApprovedConfirmedCanNotCheckCreditLimit'
        );
    }
}
