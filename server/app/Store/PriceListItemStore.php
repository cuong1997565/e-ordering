<?php
namespace App\Store;

use App\Models\AppModel;
use App\Models\PriceListItem;
use App\Models\Error;

class PriceListItemStore
{
    public function save($items) {
        try {
            foreach ($items as $key => $value) {
                $itemUpdate = PriceListItem::where('price_list_id', $value['price_list_id'])
                                        ->where('product_id',  $value['product_id'])
                                        ->get();
                if(count($itemUpdate) > 0) {
                    PriceListItem::where('price_list_id', $value['price_list_id'])
                        ->where('product_id',  $value['product_id'])
                        ->update([
                            'unit_price' => $value['unit_price']
                        ]);
                } else {
                    $data  = [
                        'price_list_id' => $value['price_list_id'],
                        'product_id' => $value['product_id'],
                        'grade_id' => $value['grade_id'],
                        'unit_price' => $value['unit_price']
                    ];
                    PriceListItem::insert($data);
                }
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.price.list.of.value.save.app_error', 'PriceListStore.save',
                null, $e->getMessage(), StatusInternalServerError);
        }
        return $items;
    }
}
