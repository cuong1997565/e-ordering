<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PriceListItem;

class PriceListItemController extends Controller
{
    public function getAll($id)
    {

        $result = DB::table('price_list_items')
        ->join('products','price_list_items.product_id','products.id')
        ->join('grades','grades.id','price_list_items.grade_id')
        ->select('price_list_items.*','products.display_name as display_name_product',
            'grades.display_name as display_name_grade')
        ->where('price_list_items.price_list_id', $id)->paginate(20);
        return $this->output_json(['data' => $result], 200);
    }

    public function createPriceListItem(PriceListItem $listItem) {
        if (is_string($this->data['priceItem'])) {
            $this->data['priceItem'] = json_decode($this->data['priceItem'], true);
        }

        if (is_array($this->data['priceItem']) && empty($this->data['priceItem'])) {
            unset($this->data['priceItem']);
        }

        $this->request->replace($this->data);

        if ((isset($this->data['priceItem']) && is_array($this->data['priceItem']))) {
            $pricelistitem = array_get($this->data, 'priceItem', []);
            if(count($pricelistitem) >0) {
                foreach ($pricelistitem as $key=>$value) {
                    foreach ($pricelistitem[$key] as $key1 => $value) {
                        if($key1 == 'product_display_name' || $key1 == 'grade_display_name') {
                            unset($pricelistitem[$key][$key1]);
                        }
                    }
                }
            } else {
                $pricelistitem = null;
            }
            $result = $listItem->createPriceListItem($pricelistitem);
        }
        else {
            $result = $listItem->createPriceListItem();
        }

        $this->output_json($result, 200);
    }

    public function deletePriceListItem() {
        $result = PriceListItem::where('product_id', $this->data['product_id'])
            ->where('grade_id', $this->data['grade_id'])->delete();
        $this->output_json($result, 200);
    }
}
