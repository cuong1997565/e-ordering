<?php
namespace App\Dashboard\Handlers;

use App\Console\Commands\CheckDashBoardPODate;
use App\Dashboard\ReadModel\Dashboard;
use App\Dashboard\ReadModel\Implementation\Mysql\DashboardDbContext;
use App\Dashboard\ReadModel\Implementation\Mysql\DashBoardListDbContext;
use App\DeliveryNote\Events\DeliveryNoteConfirmedFromApprovedForDashboard;
use App\Events\DeliverNoteDomain\DnApproved;
use App\Events\DeliverNoteDomain\DnApprovedConfirmed;
use App\Events\DeliverNoteDomain\DnConfirmAfterApproved;
use App\Events\DeliverNoteDomain\DnConfirmed;
use App\Events\DeliverNoteDomain\DnDrafted;
use App\Events\DeliverNoteDomain\DnWaitingForConfirmed;
use App\Events\PoDomain\PoAccepted;
use App\Events\PoDomain\PoSubmited;
use App\Events\SaleDomain\SoClosed;
use App\Events\SaleDomain\SoConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\DB;


class DashboardViewModelGenerator implements ShouldQueue{
    private $context;
    private $contextList;

    public function __construct(DashboardDbContext $context, DashBoardListDbContext $contextList)
    {
        $this->context = $context;
        $this->contextList = $contextList;
    }

    // DeliveryNote

    public function onDeliveryNoteDrafted(DnDrafted $events) {
        $dn_encode = json_encode($events->dnDrafted->deliveryNote);
        DB::beginTransaction();
        try {
            $dn_draft = $this->context->where('type', Dashboard::$deliveryNoteDraftSummary)->lockForUpdate()->first();
            if (!$dn_draft){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$deliveryNoteDraftSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $dn_encode);
            } else {
                $value = $dn_draft->value + 1;
                $dn_draft->value = $value;
                $dn_draft->save();
                $this->CreateOrUpdateDashboardItems($dn_draft->id, $dn_encode);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onDeliveryNoteConfirmedFromApproved($event) {
        $dn_encode = json_encode($event->dnConfirmed->deliveryNote);
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

    public function onDeliveryNoteWaitingForConfirmed($event_waiting) {
        $dn_encode = json_encode($event_waiting->dnWaitingForConfirmed->deliveryNote);
        DB::beginTransaction();
        try {
            $dn_waiting = $this->context->where('type', Dashboard::$deliveryNoteWaitingForConfirmSummary)->lockForUpdate()->first();
            if (!$dn_waiting){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$deliveryNoteWaitingForConfirmSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $dn_encode);
            } else {
                $value = $dn_waiting->value + 1;
                $dn_waiting->value = $value;
                $dn_waiting->save();
                $this->CreateOrUpdateDashboardItems($dn_waiting->id, $dn_encode);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onDeliveryNoteApproved($event_approve) {
        $dn_encode = json_encode($event_approve->dnApproved->deliveryNote);
        DB::beginTransaction();
        try {
            $dn_approve = $this->context->where('type', Dashboard::$deliveryNoteApprovedSummary)->lockForUpdate()->first();
            if (!$dn_approve){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$deliveryNoteApprovedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $dn_encode);
            } else {
                $value = $dn_approve->value + 1;
                $dn_approve->value = $value;
                $dn_approve->save();
                $this->CreateOrUpdateDashboardItems($dn_approve->id, $dn_encode);
            }

            $dn_waiting = $this->context->where('type', Dashboard::$deliveryNoteWaitingForConfirmSummary)->lockForUpdate()->first();
            if($dn_waiting){
                if($dn_waiting->value > 0){
                    $val = $dn_waiting->value - 1;
                    $dn_waiting->value = $val;
                    $dn_waiting->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onDeliveryNoteConfirmAfterApproved($event_CAA) {
        $dn_encode = json_encode($event_CAA->dnConfirmAfterApproved->deliveryNote);
        DB::beginTransaction();
        try {
            $dn_CAA = $this->context->where('type', Dashboard::$deliveryNoteConfirmedSummary)->lockForUpdate()->first();
            if (!$dn_CAA){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$deliveryNoteConfirmedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $dn_encode);
            } else {
                $value = $dn_CAA->value + 1;
                $dn_CAA->value = $value;
                $dn_CAA->save();
                $this->CreateOrUpdateDashboardItems($dn_CAA->id, $dn_encode);
            }

            $dn_approved = $this->context->where('type', Dashboard::$deliveryNoteApprovedSummary)->lockForUpdate()->first();
            if($dn_approved){
                if($dn_approved->value > 0){
                    $val = $dn_approved->value - 1;
                    $dn_approved->value = $val;
                    $dn_approved->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    // End DeliveryNote

    // Po
    public function onPoSubmited($event_PoSubmited) {
        $po_encode = json_encode($event_PoSubmited->poSubmited->Po);
        DB::beginTransaction();
        try {
            $po_submited = $this->context->where('type', Dashboard::$PoSubmitedSummary)->lockForUpdate()->first();
            if (!$po_submited){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$PoSubmitedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $po_encode);
            } else {
                $value = $po_submited->value + 1;
                $po_submited->value = $value;
                $po_submited->save();
                $this->CreateOrUpdateDashboardItems($po_submited->id, $po_encode);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onPoExpired($poExpired, $countPoExpired)
    {
        $po_encode = json_encode($poExpired);
        DB::beginTransaction();
        try {
            $po_expired = $this->context->where('type', Dashboard::$PoExpiredSummary)->lockForUpdate()->first();
            if(!$po_expired) {
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$PoExpiredSummary, 'value' => $countPoExpired]);
                $this->CreateOrUpdateDashboardItems($id, $po_encode);
            } else {
                $this->context->where('type', Dashboard::$PoExpiredSummary)->lockForUpdate()->update(['value' => $countPoExpired]);
                $this->CreateOrUpdateDashboardItems($po_expired->id, $po_encode);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onPoAccepted($event_PoAccepted) {
        $po_encode = json_encode($event_PoAccepted->poAccept->Po);
        $so_encode = json_encode($event_PoAccepted->poAccept->So);
        DB::beginTransaction();
        try {
            $po_accepted = $this->context->where('type', Dashboard::$PoAcceptedSummary)->lockForUpdate()->first();
            $so_drafted = $this->context->where('type', Dashboard::$SoDraftedSummary)->lockForUpdate()->first();
            if (!$po_accepted){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$PoAcceptedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $po_encode);
            } else {
                $value = $po_accepted->value + 1;
                $po_accepted->value = $value;
                $po_accepted->save();
                $this->CreateOrUpdateDashboardItems($po_accepted->id, $po_encode);
            }

            if (!$so_drafted){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$SoDraftedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $so_encode);
            } else {
                $value = $so_drafted->value + 1;
                $so_drafted->value = $value;
                $so_drafted->save();
                $this->CreateOrUpdateDashboardItems($so_drafted->id, $so_encode);
            }

            $po_submited = $this->context->where('type', Dashboard::$PoSubmitedSummary)->lockForUpdate()->first();
            if($po_submited){
                if($po_submited->value > 0){
                    $val = $po_submited->value - 1;
                    $po_submited->value = $val;
                    $po_submited->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }
    // End Po

    // SO
    public function onSoExpired($soExpired, $countSoExpired)
    {
        $so_encode = json_encode($soExpired);
        DB::beginTransaction();
        try {
            $so_expired = $this->context->where('type', Dashboard::$SoExpiredSummary)->lockForUpdate()->first();
            if(!$so_expired) {
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$SoExpiredSummary, 'value' => $countSoExpired]);
                $this->CreateOrUpdateDashboardItems($id, $so_encode);
            } else {
                $this->context->where('type', Dashboard::$SoExpiredSummary)->lockForUpdate()->update(['value' => $countSoExpired]);
                $this->CreateOrUpdateDashboardItems($so_expired->id, $so_encode);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onSoConfirmed($event_confirmed) {
        $so_encode = json_encode($event_confirmed->soConfirmed->So);
        DB::beginTransaction();
        try {
            $so_confirmed = $this->context->where('type', Dashboard::$SoConfirmedSummary)->lockForUpdate()->first();
            if(!$so_confirmed) {
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$SoConfirmedSummary, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $so_encode);
            } else {
                $value = $so_confirmed->value + 1;
                $so_confirmed->value = $value;
                $so_confirmed->save();
                $this->CreateOrUpdateDashboardItems($so_confirmed->id, $so_encode);
            }

            $so_drafted = $this->context->where('type', Dashboard::$SoDraftedSummary)->lockForUpdate()->first();
            if($so_drafted){
                if($so_drafted->value > 0) {
                    $val = $so_drafted->value - 1;
                    $so_drafted->value = $val;
                    $so_drafted->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onSoClosed($event_closed) {
        $so_encode = json_encode($event_closed->soClosed->So);
        DB::beginTransaction();
        try {
            $po_closed = $this->context->where('type', Dashboard::$PoClosedSummany)->lockForUpdate()->first();
            $so_closed = $this->context->where('type', Dashboard::$SoClosedSummany)->lockForUpdate()->first();
            if (!$po_closed){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$PoClosedSummany, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $so_encode);
            } else {
                $value = $po_closed->value + 1;
                $po_closed->value = $value;
                $po_closed->save();
                $this->CreateOrUpdateDashboardItems($po_closed->id, $so_encode);
            }

            if (!$so_closed){
                $id = DB::table('dashboards')->insertGetId(['type' => Dashboard::$SoClosedSummany, 'value' => 1]);
                $this->CreateOrUpdateDashboardItems($id, $so_encode);
            } else {
                $value = $so_closed->value + 1;
                $so_closed->value = $value;
                $so_closed->save();
                $this->CreateOrUpdateDashboardItems($so_closed->id, $so_encode);
            }

            $po_accepted = $this->context->where('type', Dashboard::$PoAcceptedSummary)->lockForUpdate()->first();
            if($po_accepted){
                if($po_accepted->value > 0) {
                    $val = $po_accepted->value - 1;
                    $po_accepted->value = $val;
                    $po_accepted->save();
                }
            }

            $so_confirmed = $this->context->where('type', Dashboard::$SoConfirmedSummary)->lockForUpdate()->first();
            if($so_confirmed){
                if($so_confirmed->value > 0){
                    $val = $so_confirmed->value - 1;
                    $so_confirmed->value = $val;
                    $so_confirmed->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }
    // End SO

    // dashboard list
    public function onCreditLimitExpired($expired) {
        $expired_encode = json_encode($expired);
        DB::beginTransaction();
        try {
            $cl_expired = $this->contextList->where('type', Dashboard::$creditlimitListExpiredSummary)->lockForUpdate()->first();
            if(!$cl_expired) {
                DB::table('dashboard_lists')->insert(['type' => Dashboard::$creditlimitListExpiredSummary, 'value' => $expired_encode]);
            } else {
                $this->contextList->where('type', Dashboard::$creditlimitListExpiredSummary)->lockForUpdate()->update(['value' => $expired_encode]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }

    public function onCreditLimitUpcomingExpired($upComingExpred) {
        $upcoming_expired_encode = json_encode($upComingExpred);
        DB::beginTransaction();
        try {
            $cl_upcoming_expired = $this->contextList->where('type', Dashboard::$creditlimitListUpcomingExpiredSummary)->lockForUpdate()->first();
            if(!$cl_upcoming_expired) {
                DB::table('dashboard_lists')->insert(['type' => Dashboard::$creditlimitListUpcomingExpiredSummary, 'value' => $upcoming_expired_encode]);
            } else {
                $this->contextList->where('type', Dashboard::$creditlimitListUpcomingExpiredSummary)->lockForUpdate()->update(['value' => $upcoming_expired_encode]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    }
    // end dashboard list
    public function subscribe($events) {
        // DeliveryNote
        $events->listen(
            DnConfirmed::class,
            self::class.'@'.'onDeliveryNoteConfirmedFromApproved'
        );
        $events->listen(
            DnDrafted::class,
            self::class.'@'.'onDeliveryNoteDrafted'
        );
        $events->listen(
            DnWaitingForConfirmed::class,
            self::class.'@'.'onDeliveryNoteWaitingForConfirmed'
        );
//        $events->listen(
//            DnApprovedConfirmed::class,
//            self::class.'@'.'onDnApprovedConfirmedCanNotCheckCreditLimit'
//        );
        $events->listen(
            DnApproved::class,
            self::class.'@'.'onDeliveryNoteApproved'
        );
        $events->listen(
            DnConfirmAfterApproved::class,
            self::class.'@'.'onDeliveryNoteConfirmAfterApproved'
        );
        // End DeliveryNote

        // Po
        $events->listen(
            PoSubmited::class,
            self::class.'@'.'onPoSubmited'
        );
        $events->listen(
            PoAccepted::class,
            self::class.'@'.'onPoAccepted'
        );
        // End Po

        // So
        $events->listen(
            SoConfirmed::class,
            self::class.'@'.'onSoConfirmed'
        );
        $events->listen(
            SoClosed::class,
            self::class.'@'.'onSoClosed'
        );
        // End So
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

}
