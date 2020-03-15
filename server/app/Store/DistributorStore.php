<?php

namespace App\Store;

use App\Models;
use App\Models\Distributor;
use App\Models\DistributorProduct;
use App\App\CreditAccountRepository;
use App\Models\Error;
use App\Models\Factory;
use Illuminate\Support\Facades\DB;

class DistributorStore
{
    //get id distributor
    public function get($id)
    {
        if (! is_integer($id))
        {
            return Error::NewAppError('model.distributor.is_valid.id.app_error', 'DistributorStore.get', null, "id={$id}",StatusBadRequest);
        }

        try {
            $distributor = Distributor::find($id);
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.finding.app_error', 'DistributorStore.get', null, "id=" . $id . ', ' . $e->getMessage(),StatusInternalServerError);
        }

        if($distributor == null)
        {
            return Error::NewAppError('store.distributor.finding.app_error', 'DistributorStore.get', null, "id={$id}",StatusNotFound);

        }

        return $distributor;
    }

    public function countDistributorBySaleOrderIds($saleOrderIds) {
        if (!is_array($saleOrderIds)) {
            return Error::NewAppError('store.distributor_store.countDistributorBySaleOrderIds.app_error', 'DistributorStore.countDistributorBySaleOrderIds', null, '', StatusBadRequest);
        }

        try {
            $count = Distributor::join('sale_orders', 'sale_orders.distributor_id', '=', 'distributors.id')
                ->whereIn('sale_orders.id', $saleOrderIds)
                ->select('distributors.*')
                ->groupBy('distributors.id')
                ->get()->count();
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.count_by_sale_order_ids.app_error', 'DistributorStore.countDistributorBySaleOrderIds', null, $e->getMessage(), StatusInternalServerError);
        }

        return $count;
    }
    /*
     * $creditAccount : ['distributor_id', 'amount', 'hold_amount', 'available_amount', 'credit_limit']
     * */
    public function save(Distributor $distributor, $creditAccount)
    {
        if ($distributor->id !== null && $distributor->id !== 0) {
            return Error::NewAppError('store.distributor.save.existing.app_error', 'DistributorStore.save', null, "id={$distributor->id}", StatusBadRequest);
        }

        $distributor->id = null;

        try {
            if (!$data = $distributor->toInstanceArray()) {
                return Error::NewAppError('store.distributor.save.app_error', 'DistributorStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.save.app_error', 'DistributorStore.save', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        DB::beginTransaction();

        try {
            $distributor = Distributor::create($data);

            $creditAccount['distributor_id'] = $distributor->id;

            $creditAccountRepository = new CreditAccountRepository();

            /*
             * create credit account
             * */
             $result = $creditAccountRepository->createCreditAccount($creditAccount);

            if (is_object($result) && get_class($result) == Error::class) {
                throw new \Error($result->Id);
            }

        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.distributor.save.app_error', 'DistributorStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $distributor;
    }

    public function update(Distributor $distributor, $creditAccount)
    {
        if (!$distributor->id || !is_integer($distributor->id)) {
            return Error::NewAppError('model.distributor.is_valid.id.app_error', 'DistributorStore.update', null, "id={$distributor->id}", StatusBadRequest);
        }


        try {
            if (!Distributor::find($distributor->id)) {
                return Error::NewAppError('store.distributor.update.find.app_error', 'DistributorStore.update', null, "id=" . $distributor->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.update.finding.app_error', 'DistributorStore.update', null, "id=" . $distributor->id . $e->getMessage(), StatusInternalServerError);
        }

        try {
            if (!$data = $distributor->toInstanceArray()) {
                return Error::NewAppError('store.distributor.save.app_error', 'DistributorStore.save', null, 'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.update.app_error', 'DistributorStore.update', null, 'cannot convert instance to array ', StatusInternalServerError);
        }

        DB::beginTransaction();
        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            Distributor::where('id', $distributor->id)
                ->update($data);


            $creditAccountRepository = new CreditAccountRepository();

            /*
             * update credit account
             * */
            $creditAccount['distributor_id'] = $distributor->id;

            $result = $creditAccountRepository->updateCreditAccountDistributor($creditAccount);

            if (is_object($result) && get_class($result) == Error::class) {
                throw new \Error($result->Id);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return Error::NewAppError('store.distributor.update.updating.app_error', 'DistributorStore.update', null, "id=" . $distributor->id . $e->getMessage(), StatusInternalServerError);
        }

        return $distributor;
    }

    public function searchByName($name)
    {
        if (!is_string($name)) {
            return Error::NewAppError('model.distributor.is_valid.name.app_error', 'DistributorStore.searchByName', null, "name={$name}", StatusBadRequest);
        }
        try {
            $result = Distributor::where('name', 'like', $name . '%')->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.search_by_name.app_error', 'DistributorStore.searchByName', ['Name' => $name], 'name='. $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }

    public function getByName($name)
    {
        if(!is_string($name)) {
            return Error::NewAppError('model.distributor.is_valid.name.app_error','DistributorStore.getByName', null, "name={$name}",StatusBadRequest);
        }

        $name = urldecode($name);
        try {
            $result = Distributor::where('name', $name)->first();
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.get_by_name.app_error', 'DistributorStore.getByName', ['Name' => $name], 'name='. $name . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }

    public function productGetDistributor($product_id, $distributor_id)
    {
        try {
            $result = DistributorProduct::where([['product_id', $product_id], ['distributor_id', $distributor_id]])->get();
        } catch (\Exception $e) {
            return Error::NewAppError('store.distributor.distributor_get_product.app_error', 'DistributorStore.productGetDistributor',
                null, $e->getMessage(), StatusInternalServerError);
        }

        return $result;
    }
}
