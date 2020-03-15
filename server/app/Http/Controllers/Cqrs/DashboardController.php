<?php
namespace App\Http\Controllers\Cqrs;

use App\App\AuthorizationRepository;
use App\Dashboard\ReadModel\IDashboardDao;
use App\DeliveryNote\Events\DeliveryNoteConfirmedFromApprovedForDashboard;
use App\Http\Context\Context;
use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller {
    public $dao;
    public function __construct(Request $request, Context $context, AuthorizationRepository $authorization, IDashboardDao $dao)
    {
        parent::__construct($request, $context, $authorization);
        $this->dao = $dao;
    }

    public function getAll() {
        $this->output(['data' => $this->dao->getAll(), 'cl_expired' => $this->dao->getCreditLimitExpired()], 200);
    }

    public function test() {
        event(new DeliveryNoteConfirmedFromApprovedForDashboard(
            1, 2, 3, 5, 6
        ));
        $this->output($this->dao->getAll(), 200);
    }

    public function getAllList() {

          $po_submit = DB::table('orders')
              ->join('factories', 'factories.id', '=', 'orders.factory_id')
              ->join('customers', 'customers.id', '=', 'orders.creator_id')
              ->where('orders.status' , SUBMITED_ORDER)
              ->select('orders.po_number as po_number','orders.status as status','factories.name as factory_name','customers.name as customer_name')
              ->get();

          $po_accept =  DB::table('orders')
              ->join('factories', 'factories.id', '=', 'orders.factory_id')
              ->join('customers', 'customers.id', '=', 'orders.creator_id')
              ->where('orders.status' , SALES_ACCEPTED_ORDER)
              ->orwhere('orders.status' , DELIVERING_ORDER)
              ->select('orders.po_number as po_number','orders.status as status','factories.name as factory_name','customers.name as customer_name')
              ->get();

          $po_expired = DB::table('orders')
            ->join('factories', 'factories.id', '=', 'orders.factory_id')
            ->join('customers', 'customers.id', '=', 'orders.creator_id')
            ->where('orders.deliver_date' , '<', Carbon::today())
            ->where('orders.status' , '!=', CLOSED_ORDER)
            ->select('orders.po_number as po_number','orders.status as status','factories.name as factory_name','customers.name as customer_name','orders.deliver_date as deliver_date')
            ->get();

          $po_close =  DB::table('orders')
              ->join('factories', 'factories.id', '=', 'orders.factory_id')
              ->join('customers', 'customers.id', '=', 'orders.creator_id')
              ->where('orders.status' , CLOSED_ORDER)
              ->select('orders.po_number as po_number','orders.status as status','factories.name as factory_name','customers.name as customer_name')
              ->get();

          $so_draf = DB::table('sale_orders')
              ->join('factories', 'factories.id', '=', 'sale_orders.factory_id')
              ->join('users', 'users.id', '=', 'sale_orders.sale_person_id')
              ->where('sale_orders.status', SO_DRAFT_STATUS)
              ->select('sale_orders.so_number as so_number','sale_orders.status as status','factories.name as factory_name','users.name as sale_name')
              ->get();

          $so_open =  DB::table('sale_orders')
              ->join('factories', 'factories.id', '=', 'sale_orders.factory_id')
              ->join('users', 'users.id', '=', 'sale_orders.sale_person_id')
              ->where('sale_orders.status', SO_OPEN_STATUS)
              ->select('sale_orders.so_number as so_number','sale_orders.status as status','factories.name as factory_name','users.name as sale_name')
              ->get();

          $so_expired = DB::table('sale_orders')
            ->join('factories', 'factories.id', '=', 'sale_orders.factory_id')
            ->join('users', 'users.id', '=', 'sale_orders.sale_person_id')
            ->where('sale_orders.so_date' , '<', Carbon::today())
            ->where('sale_orders.status', '!=' , SO_CLOSE_STATUS)
            ->select('sale_orders.so_number','sale_orders.status as status','factories.name as factory_name','users.name as sale_name',
                'sale_orders.so_date as so_date')
            ->get();

          $so_close =  DB::table('sale_orders')
            ->join('factories', 'factories.id', '=', 'sale_orders.factory_id')
            ->join('users', 'users.id', '=', 'sale_orders.sale_person_id')
            ->where('sale_orders.status', SO_CLOSE_STATUS)
            ->select('sale_orders.so_number as so_number','sale_orders.status as status','factories.name as factory_name','users.name as sale_name')
            ->get();

          $dn_draft = DB::table('delivery_notes')
              ->join('factories', 'factories.id', '=', 'delivery_notes.factory_id')
              ->join('distributors', 'distributors.id', '=', 'delivery_notes.distributor_id')
              ->join('users', 'users.id', '=', 'delivery_notes.sale_person_id')
              ->where('delivery_notes.status', DeliveryNote::$deliveryDraftStatus)
              ->orWhere('delivery_notes.status', DeliveryNote::$deliveryWaitingApproveWhenOver)
              ->orWhere('delivery_notes.status', DeliveryNote::$deliveryApproved)
              ->select('delivery_notes.dn_number','delivery_notes.status','factories.name as factory_name',
                  'distributors.name as distributor_name', 'users.name as user_name')
              ->get();

          $dn_waitingForConfirm =  DB::table('delivery_notes')
              ->join('factories', 'factories.id', '=', 'delivery_notes.factory_id')
              ->join('distributors', 'distributors.id', '=', 'delivery_notes.distributor_id')
              ->join('users', 'users.id', '=', 'delivery_notes.sale_person_id')
              ->where('delivery_notes.status', DeliveryNote::$deliveryWaitingApproveWhenOver)
              ->select('delivery_notes.dn_number','delivery_notes.status','factories.name as factory_name',
                  'distributors.name as distributor_name', 'users.name as user_name')
              ->get();

        $dn_approved = DB::table('delivery_notes')
              ->join('factories', 'factories.id', '=', 'delivery_notes.factory_id')
              ->join('distributors', 'distributors.id', '=', 'delivery_notes.distributor_id')
              ->join('users', 'users.id', '=', 'delivery_notes.sale_person_id')
              ->where('delivery_notes.status', DeliveryNote::$deliveryApproved)
              ->select('delivery_notes.dn_number','delivery_notes.status','factories.name as factory_name',
                  'distributors.name as distributor_name', 'users.name as user_name')
              ->get();

        $dn_confirm =  DB::table('delivery_notes')
            ->join('factories', 'factories.id', '=', 'delivery_notes.factory_id')
            ->join('distributors', 'distributors.id', '=', 'delivery_notes.distributor_id')
            ->join('users', 'users.id', '=', 'delivery_notes.sale_person_id')
            ->where('delivery_notes.status', DeliveryNote::$deliveryConfirmStatus)
            ->select('delivery_notes.dn_number','delivery_notes.status','factories.name as factory_name',
                'distributors.name as distributor_name', 'users.name as user_name')
            ->get();

          $this->output_json([
            'po_submit' => $po_submit,
            'po_accept' => $po_accept,
            'po_expired' => $po_expired,
            'po_close' => $po_close,
            'so_draf' => $so_draf,
            'so_open' => $so_open,
            'so_expired' => $so_expired,
            'so_close' => $so_close,
            'dn_draft' => $dn_draft,
            'dn_waitingforconfirm' => $dn_waitingForConfirm,
            'dn_approved' => $dn_approved,
            'dn_confirm' => $dn_confirm
          ], 200);
    }
}
