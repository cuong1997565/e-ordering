<?php

namespace App\App;

use App\Models\Customer;
use App\Models\Session;
use App\Models\Error;
use App\Store\CustomerStore;
use App\Store\DistributorStore;
use App\Store\FactoryStore;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class FactoryRepository
{
    public function getAllWithFields() {
        $result = (new FactoryStore())->getAllWithFields(['id', 'name','code']);

        if (empty($result)) {
            $result = Error::NewAppError('store.factory.get_all_with_fields', 'CustomerRepository.getAllWithFields', [], '', StatusNotFound);
        }

        return $result;
    }

    public function countFactoriesBySaleOrderIds($saleOrderIds) {
        return (new FactoryStore())->countFactoriesBySaleOrderIds($saleOrderIds);
    }
}
