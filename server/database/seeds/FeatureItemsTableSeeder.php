<?php

use Illuminate\Database\Seeder;
use App\Models\FeatureItem;

class FeatureItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(FeatureItem::class, 50)->create();
//        $data = [];
//        for($i = 101; $i < 131; $i++) {
//            $featureitem = array(1,2);
//
//            array_push($data, [
//                'product_id' => $i,
//                'featureitem_id' => 1,
//            ]);
//
//        }
//        DB::table('product_featureitem')->insert($data);

//        $data = [];
//        for($i = 101; $i < 131; $i++) {
//            $featureitem = array(1,2);
//            $grade = array(1,2,3,4);
//
//            array_push($data, [
//                'product_id' => $i,
//                'grade_id' => 2,
//                'price_list_id' => 1,
//                'unit_price' => 100000
//            ]);
//
//        }
//
//        DB::table('price_list_items')->insert($data);
        $data = [];
        for ($i = 15; $i < 53; $i ++) {
            array_push($data, [
                'distributor_id' => $i,
                'amount' => 20000000,
                'hold_amount' => 0,
                'available_amount' => 20000000,
                'credit_limit' => 10000
            ]);
        }

        DB::table('credit_accounts')->insert($data);
    }
}
