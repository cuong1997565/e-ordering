<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factory
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(\App\Models\Customer::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'type' => 0,
        'password' => Faker\Provider\Miscellaneous::md5(),
        'auth_token' => Faker\Provider\Miscellaneous::md5(),
    ];
});

$factory->define(\App\Models\Distributor::class, function (Faker\Generator $faker) {
    $area = \App\Models\Area::all()->pluck('id')->toArray();
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'address' => $faker->address,
        'phone' => $faker->biasedNumberBetween(0, 10),
        'area_id' => $faker->randomElement($area),
        'code' => $faker->uuid,
        'tax_code' => $faker->uuid,
        'contact_person' => $faker->name,
        'active' => $faker->biasedNumberBetween(0, 1),

    ];
});

$factory->define(\App\Models\Area::class, function (Faker\Generator $faker) {
    return [
        'parent_id' => $faker->uuid,
        'name' => $faker->name,
        'code' => $faker->uuid,
//        'order' => $faker->biasedNumberBetween(0, 4),
        'active' => $faker->biasedNumberBetween(0, 1),
        'level' => $faker->biasedNumberBetween(1, 4),
    ];
});

$factory->define(\App\Models\Factory::class, function (Faker\Generator $faker){
    return [
        'name' => $faker->name,
        'code' => $faker->postcode,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});


$factory->define(\App\Models\Store::class, function (Faker\Generator $faker){
    $dataFactory = factory(\App\Models\Factory::class, 1)->create();
    $factory = \App\Models\Factory::all()->pluck('id')->toArray();
    return [
        'code' => $faker->postcode,
        'factory_id' => $faker->randomElement($factory),
        'name' => $faker->name,
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});


$factory->define(\App\Models\Product::class, function (Faker\Generator $faker){
    $category = \App\Models\Category::all()->pluck('id')->toArray();

    $product_type = \App\Models\ProductType::all()->pluck('id')->toArray();

    $uom = \App\Models\Uom::all()->pluck('id')->toArray();

    $grade_group = \App\Models\GradeGroup::all()->pluck('id')->toArray();

    $year  = rand(2014, 2018);
    $month = rand(1, 12);
    $day   = rand(1, 28);
    $hour  = rand(7, 17);
    $minute = $faker->randomElement([00, 15, 30, 45]);
    $date   = Carbon::create($year, $month, $day, $hour, $minute, 0);
    return [
        'category_id' => $faker->randomElement($category),
        'product_type_id' => $faker->randomElement($product_type),
        'uom_id' => $faker->randomElement($uom),
        'grade_group_id' => $faker->randomElement($grade_group),
        'short_name' => $faker->name,
        'display_name' => $faker->name,
        'is_life_management' => $faker->biasedNumberBetween(0,1),
        'code'        => $faker->uuid,
        'name'        => 'Sảm phẩm '. $faker->name,
        'image' =>  'Product/'.$faker->randomElement(['ihAPUstp4bKzaplQMn4SHZpMbDWksczobENIHzvc.jpg',
                'JpOuKGMnBU8pKBoB2L7m1L9Xrs55Jq02b7SLVqlt.jpg','ZPsGhGjIXi70e3F7va1vTpZSzmArxahi6KDyP86A.jpg']),
        'active' => $faker->biasedNumberBetween(0,1),
        'max_age' => $faker->biasedNumberBetween(1,20),
        'release_date' =>$date->addDays(rand(1, 14))->format('Y-m-d'),
    ];
});


//$factory->define(\App\Models\Brand::class, function (Faker\Generator $faker){
//    return [
//        'name' => $faker->name,
//        'code' => $faker->uuid,
//        'active' => $faker->biasedNumberBetween(0, 1),
//    ];
//});


$factory->define(\App\Models\Brand::class, function (Faker\Generator $faker){
    return [
        'name' => $faker->name,
        'code' => $faker->uuid,
        'active' => $faker->biasedNumberBetween(0, 1),
    ];
});

$factory->define(\App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'parent_id' => 0,
        'name' => $faker->name,
        'order' => $faker->biasedNumberBetween(0, 4),
        'active' => $faker->biasedNumberBetween(0, 1),
        'level' => 1,
        'code' => $faker->uuid
    ];
});

$factory->define(\App\Models\Order::class, function (Faker\Generator $faker) {
//    $dataStore = factory(\App\Models\Store::class, 10)->create();
    $factory = \App\Models\Factory::all()->pluck('id')->toArray();
    $customer = \App\Models\Customer::all()->pluck('id')->toArray();

    $year  = rand(2014, 2018);
    $month = rand(1, 12);
    $day   = rand(1, 28);
    $hour  = rand(7, 17);
    $minute = $faker->randomElement([00, 15, 30, 45]);
    $date   = Carbon::create($year, $month, $day, $hour, $minute, 0);
    return [
        'code' => $faker->uuid,
        'factory_id' => $faker->randomElement($factory),
        'creator_id' => $faker->randomElement($customer),
        'status' => $faker->biasedNumberBetween(1, 8),
        'creator_note' => $faker->realText(rand(10,20)),
        'deliver_date' =>$date->addDays(rand(1, 14))->format('Y-m-d'),
        'deliver_actual' =>$date->addDays(rand(1, 14))->format('Y-m-d'),
        'note' => $faker->realText(rand(10,20)),
        'total'       =>  $faker->biasedNumberBetween(100, 9000000)
    ];
});

$factory->define(\App\Models\OrderProduct::class, function (Faker\Generator $faker){
   $product = \App\Models\Product::all()->pluck('id')->toArray();
   $order = \App\Models\Order::all()->pluck('id')->toArray();

   return [
       'product_id' => $faker->randomElement($product),
       'order_id' => $faker->randomElement($order),
       'amount' => $faker->biasedNumberBetween(1, 50),
   ];
});

$factory->define(\App\Models\DistributorProduct::class, function (Faker\Generator $faker){
    $product = \App\Models\Product::all()->pluck('id')->toArray();
    $distributor = \App\Models\Distributor::all()->pluck('id')->toArray();

    return [
        'product_id' => $faker->randomElement($product),
        'distributor_id' => $faker->randomElement($distributor),
        'min_quantity' => $faker->biasedNumberBetween(1, 50),
        'max_quantity' => $faker->biasedNumberBetween(100, 300),
        'max_hold_age' => $faker->biasedNumberBetween(1, 50),
    ];
});

$factory->define(\App\Models\CreditAccount::class, function (Faker\Generator $faker){
    $distributor = \App\Models\Distributor::all()->pluck('id')->toArray();

    return [
        'distributor_id' => $faker->randomElement($distributor),
        'amount' => $faker->biasedNumberBetween(10000, 500000),
        'hold_amount' => $faker->biasedNumberBetween(1000, 30000),
        'available_amount' => $faker->biasedNumberBetween(10000, 500000),
        'credit_limit' => $faker->biasedNumberBetween(1000, 50000),
    ];
});

$factory->define(\App\Models\CreditTransaction::class, function (Faker\Generator $faker){
    $credit = \App\Models\CreditAccount::all()->pluck('id')->toArray();

    return [
        'credit_id' => $faker->randomElement($credit),
        'transaction_type' => $faker->biasedNumberBetween(0, 1),
        'is_manual_transaction' => $faker->biasedNumberBetween(0, 1),
        'reference' => $faker->address,
        'description' => $faker->address,
        'is_hold_transaction' => $faker->biasedNumberBetween(0, 1),
        'transaction_amount' => $faker->biasedNumberBetween(10000, 500000),
    ];
});

$factory->define(\App\Models\UomMultiple::class,  function (Faker\Generator $faker){
    $uom = \App\Models\Uom::all()->pluck('id')->toArray();
    return [
        'uom_id' => $faker->randomElement($uom),
        'name' => $faker->name,
        'code' => $faker->uuid,
        'display_name' => $faker->name,
        'description' => $faker->realText(rand(10, 60)),
        'conversion_rate' => $faker->biasedNumberBetween(0, 4),
        'isrounded' => $faker->biasedNumberBetween(0, 1),
        'round_priority' => $faker->biasedNumberBetween(1,3),
        'active' => 1
    ];
});

$factory->define(\App\Models\Uom::class,  function (Faker\Generator $faker){
    return [
        'name' => $faker->name,
        'code' => $faker->uuid,
        'display_name' => $faker->name,
        'description' => $faker->realText(rand(10, 60)),
        'is_based_uom' => true,
        'conversion_rate' => $faker->biasedNumberBetween(0, 10),
        'isrounded' => $faker->biasedNumberBetween(false, true),
        'round_priority' => $faker->biasedNumberBetween(1,3),
        'active' => 1
    ];
});


$factory->define(\App\Models\GradeGroup::class,  function (Faker\Generator $faker){
    return [
        'name' => $faker->name,
        'code' => $faker->uuid,
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});

$factory->define(\App\Models\Grade::class,  function (Faker\Generator $faker){
    $grade_group = \App\Models\GradeGroup::all()->pluck('id')->toArray();
    return [
        'grade_group_id' => $faker->randomElement($grade_group),
        'name' => $faker->name,
        'code' => $faker->uuid,
        'display_name' => $faker->name,
        'active' => $faker->biasedNumberBetween(0,1),
    ];
});


$factory->define(\App\Models\ProductType::class,  function (Faker\Generator $faker){
    return [
        'name' => $faker->name,
        'code' => $faker->uuid,
        'description' => $faker->realText(rand(10, 60)),
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});

$factory->define(\App\Models\Features::class,  function (Faker\Generator $faker){
    return [
        'name' => $faker->name,
        'display_name' => $faker->name,
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});

$factory->define(\App\Models\ProductFeatureItem::class,  function (Faker\Generator $faker){
    $featureitem_id = \App\Models\FeatureItem::all()->pluck('id')->toArray();
    return [
        'featureitem_id' => $faker->randomElement($featureitem_id)
    ];
});



$factory->define(\App\Models\FeatureItem::class,  function (Faker\Generator $faker){
    $feature_id = \App\Models\Features::all()->pluck('id')->toArray();
    return [
        'feature_id' => $faker->randomElement($feature_id),
        'name' => $faker->name,
        'display_name' => $faker->name,
        'sequence' => $faker->name,
        'is_active' => $faker->biasedNumberBetween(0,1),
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});

$factory->define(\App\Models\Attributes::class,  function (Faker\Generator $faker){
    $product_type = \App\Models\ProductType::all()->pluck('id')->toArray();
    return [
        'product_type_id' => $faker->randomElement($product_type),
        'name' => $faker->name,
        'code' => $faker->uuid,
        'description' =>  $faker->realText(rand(10, 60)),
        'type' => $faker->biasedNumberBetween(1,3),
        'active' => $faker->biasedNumberBetween(0,1)

    ];
});

$factory->define(\App\Models\AttributeListsOfValue::class,  function (Faker\Generator $faker){
    $attribute = \App\Models\Attributes::where('type', Attributes_Type_List)->pluck('id')->toArray();
    return [
        'attribute_id' => $faker->randomElement($attribute),
        'value' => $faker->name,
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});

$factory->define(\App\Models\ProductStore::class,  function (Faker\Generator $faker){
    $dataStore = factory(\App\Models\Store::class, 1)->create();
    $store = \App\Models\Store::all()->pluck('id')->toArray();

    $dataProduct = factory(\App\Models\Product::class, 1)->create();
    $product = \App\Models\Product::all()->pluck('id')->toArray();
    return [
        'store_id' => $faker->randomElement($store),
        'product_id' => $faker->randomElement($product)
    ];
});

$factory->define(\App\Models\PriceList::class,  function (Faker\Generator $faker){
    return [
        'code' => $faker->uuid,
        'name' => $faker->name,
        'is_default' => $faker->biasedNumberBetween(0,1),
        'active' => $faker->biasedNumberBetween(0,1)
    ];
});




$factory->define(\App\Models\SaleOrder::class,  function (Faker\Generator $faker){
    $dataOrder = factory(\App\Models\Order::class, 1)->create();
    $order = \App\Models\Order::all()->pluck('id')->toArray();

    $dataDistributor = factory(\App\Models\Distributor::class, 1)->create();
    $distributor = \App\Models\Distributor::all()->pluck('id')->toArray();

    $dataFactory = factory(\App\Models\Factory::class, 1)->create();
    $factory  = \App\Models\Factory::all()->pluck('id')->toArray();


    $dataPrice = factory(\App\Models\PriceList::class, 1)->create();
    $price  = \App\Models\PriceList::all()->pluck('id')->toArray();
    return [
        'so_number' => $faker->uuid,
        'order_id' => $faker->randomElement($order),
        'distributor_id' => $faker->randomElement($distributor),
        'factory_id'=> $faker->randomElement($factory),
        'price_list_id' => $faker->randomElement($price),
        'estimated_amount' => $faker->randomFloat(2, 1, 100 ),
        'so_date' => Carbon::now(),
        'sale_person_id' => 1, // Tham chieu toi bang user
        'status' => $faker->biasedNumberBetween(1,2),
        'note' => $faker->realText(rand(10, 60)),
    ];
});


$factory->define(\App\Models\SaleOrderItem::class,  function (Faker\Generator $faker){
    $dataProduct = factory(\App\Models\Product::class, 1)->create();
    $product = \App\Models\Product::all()->pluck('id')->toArray();

    $dataGrade = factory(\App\Models\Grade::class, 1)->create();
    $grade = \App\Models\Grade::all()->pluck('id')->toArray();

    $dataUom = factory(\App\Models\Uom::class, 1)->create();
    $uom = \App\Models\Uom::all()->pluck('id')->toArray();

    $dataUnitPrice = factory(\App\Models\PriceList::class, 1)->create();
    $UnitPrice = \App\Models\PriceList::all()->pluck('id')->toArray();

    $dataAttribute = factory(\App\Models\Attributes::class, 1)->create();
    $Attribute = \App\Models\Attributes::all()->pluck('id')->toArray();

    return [
//        'sale_order_id' => factory(\App\Models\SaleOrder::class)->create()->id,
        'product_id' => $faker->randomElement($product),
        'grade_id' => $faker->randomElement($grade),
        'uom_id' => $faker->randomElement($uom),
        'sale_quantity' => $faker->biasedNumberBetween(1,10),
        'product_attributes' => $faker->randomElement($Attribute),
        'delivered_quantity' => $faker->biasedNumberBetween(1,5),
        'remaining_quantity' => $faker->biasedNumberBetween(1,6),
        'unit_price_id' => $faker->randomElement($UnitPrice),
        'unit_price' => $faker->randomFloat(2, 1, 100 ),
        'amount' => 1,
        'status' => $faker->biasedNumberBetween(1,2),
        'sale_note' =>  $faker->realText(rand(10, 60)),
    ];
});


$factory->define(\App\Models\PriceListItem::class,  function (Faker\Generator $faker){


    $dataGrade = factory(\App\Models\Grade::class, 1)->create();
    $grade = \App\Models\Grade::all()->pluck('id')->toArray();

    return [
        'price_list_id' => 1,
        'grade_id' => $faker->randomElement($grade),
        'unit_price' => $faker->randomFloat(2, 1, 100 )
    ];
});




