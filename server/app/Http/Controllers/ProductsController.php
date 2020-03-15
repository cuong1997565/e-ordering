<?php

namespace App\Http\Controllers;

use App\App\ProductRepository;
use App\Http\Context\Context;
use App\Models\Error;
use App\Models\Product;

use App\Models\Customer;
use App\Models\ProductSearchForFactory;
use App\Models\Store\SearchProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ProductsController extends Controller
{
    public function getAll()
    {
        $product = Product::getDynamic();
        $this->output_json(['data' => $product], 200);
    }

    public function getProductDetail() {
        $product = Product::with(['featureitem' ,'productstore','pricelistitem', 'price_list_items.grade','price_list_items.price_list'])->getDynamic();
        $this->output_json(['data' => $product], 200);
    }

    ## Client list api product
    public function getClientProducts() {
        $product = Product::with(['category' => function($q) {
            $q->select(['id', 'name','parent_id']);
        },'brand' => function($b) {
            $b->select(['id','name']);
        }])
        ->whereHas('brand', function (Builder $query) {
            $query->where('active', 1);
        })->orderBy('id','desc')->getDynamic();
        $this->output_json(['data' => $product], 200);
    }       

    public function searchProductsForFactory($factory_id, Context $context) {
        $this->request->merge(['factory_id' => $factory_id]);

        $context->setRequest($this->request);

        $validate = $context->requireFactoryId();

        if ((is_object($validate)) && get_class($validate) == Error::class) {
            $this->output_json_client($validate, 200);
        }

        $this->data = $this->request->input();

        $productSearch = SearchProducts::toModel($this->data);

        $result = (new ProductRepository())->searchProductsForFactory($productSearch);

        $this->output_json(['data' => $result], 200);
    }

    ## Client API
    public function getClientSreachProducts(ProductRepository $pr)
    {
        $brand = isset($_GET['brand_id']) ? $_GET['brand_id'] : null ;
        $catefory = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        $code = isset($_GET['code']) ? $_GET['code'] : null;

        $data = $pr->getProducts($brand, $catefory, $code);

        $this->output_json(['data' => $data], 200);
    }

    public function checkAmountProduct($product_id, Context $context)
    {

        $validate = $context->requireProductAmount();

        if ((is_object($validate)) && get_class($validate) == Error::class) {
            $this->output_json_client($validate, 200);
        }

        $error = Error::NewAppError('products.check_amount.app_error', 'ProductsController.checkAmountProduct', null, "productId=" . $product_id . ', ' . "amount=" . $this->data['product_amount'] . ', ', StatusBadRequest);

        $client = new Client(); //GuzzleHttp\Client
        $Nbody = $this->data['dataAmount'];
        $arrayBody = json_decode($this->data['dataAmount']);

        $data = $arrayBody[0] . $arrayBody[1] . $arrayBody[2] . $arrayBody[9] . '64F0F1368c68D97A7885B880F365C6B8';
        $code = md5($data);
        $arrayBody[10] = $code;

        $Nbody = json_encode($arrayBody, true);

        $response = $client->request('POST', 'http://dimi-api.prime.vn/eselling/PR_ESELLING_BARCODE_CHECK', [
            'body' => $Nbody,
            'headers'  => [
                'Content-Type' => 'application/json',
            ]
        ]);

        $this->output_json(['data' =>json_decode($response->getBody()), 'code' => $code], 200);

//        if ($this->data['product_amount'] == 2) {
//            sleep(3);
//            $this->output_json_client($error, 200);
//        }
//        if ($this->data['product_amount'] == 4) {
//            sleep(3);
//            $this->output_json_client($error, 200);
//        }
//        if ($this->data['product_amount'] == 3) {
//            sleep(2);
//            $this->output_status_ok(null);
//        }
//        $this->output_json_client($error, 200);
////        $this->output_status_fail(null);
//        $this->output_status_ok(null);
    }
    public function createProduct(Product $product, Request $request)
    {

        if (is_string($this->data['featureitem'])) {
            $this->data['featureitem'] = json_decode($this->data['featureitem']);
        }

        if (is_array($this->data['featureitem']) && empty($this->data['featureitem'])) {
            unset($this->data['featureitem']);
        }

        if (is_string($this->data['pricelistitem'])) {
            $this->data['pricelistitem'] = json_decode($this->data['pricelistitem'] , true);
        }


        if (is_array($this->data['pricelistitem']) && empty($this->data['pricelistitem'])) {
            unset($this->data['pricelistitem']);
        }

        if (is_string($this->data['stores'])) {
            $this->data['stores'] = json_decode($this->data['stores']);
        }

        if (is_array($this->data['stores']) && empty($this->data['stores'])) {
            unset($this->data['stores']);
        }

        $this->request->replace($this->data);
        $this->validate($this->request,
            [
                'product_type_id' => 'required|integer',
                'category_id' => 'required|integer',
                'code' => 'required|unique:products,code|max:64|regex:/^\S*$/u|regex:/^[a-zA-Z0-9?=.*!@#$%^&*_()\-\s]+$/u',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                'uom_id'=> 'required|integer',
                'grade_group_id'=> 'required|integer',
                'short_name' => 'required',
                'display_name' => 'required',
                'release_date' => 'required|date',
                'featureitem' => 'required',
                'pricelistitem' => 'required',
                'stores' => 'required'
            ],
            [
                'product_type_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'product type']),
                'product_type_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'product type']),
                'category_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'category']),
                'category_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'category id']),
                'uom_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'uom']),
                'uom_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'uom']),
                'grade_group_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'grade group']),
                'grade_group_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'grade group']),
                'short_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'short name']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'code.max' => trans('messages.api.max.product.app_error', ['Name' => 'code', 'Value' => '64']),
                'code.regex' => trans('messages.api.regex.code.product.app_error', ['Name' => 'code']),
                'image.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'image']),
                'image.image' => trans('messages.api.image.image.app_error', ['Name' => 'image']),
                'image.mimes' => trans('messages.api.image.mimes.app_error', ['Name' => 'image', 'Value' => 'jpeg,png,jpg,gif,svg']),
                'release_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'release date']),
                'release_date.date' => trans('messages.api.date.product.app_error', ['Name' => 'release date']),
                'featureitem.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'feature item']),
                'pricelistitem.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'price list item']),
                'stores.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'product store']),

            ]);

        $product = $product->toModel($this->data);

        if ((isset($this->data['featureitem']) && is_array($this->data['featureitem']))
            || (isset($this->data['pricelistitem']) && is_array($this->data['pricelistitem']))
            || (isset($this->data['stores']) && is_array($this->data['stores']))
        ) {
            $featureitem = array_get($this->data, 'featureitem', []);
            $pricelistitem = array_get($this->data, 'pricelistitem', []);
            if(count($pricelistitem) >0) {
                foreach ($pricelistitem as $key=>$value) {
                    foreach ($pricelistitem[$key] as $key1 => $value) {
                        if($key1 == 'price_name' || $key1 == 'grade_name') {
                               unset($pricelistitem[$key][$key1]);
                        }
                    }
                    // unset($pricelistitem[$key]['price_name']);
                    // unset($pricelistitem[$key]['grade_name']);
                }
            } else {
                $pricelistitem = null;
            }
            $stores = array_get($this->data, 'stores', []);
            $result = $product->createProduct($featureitem, $pricelistitem, $stores);
        }
        else {

            $result = $product->createProduct();

        }

        $this->output_json($result, 200);
    }

    public function updateProduct($product_id, Product $product, Request $request)
    {
        if (is_string($this->data['featureitem'])) {
            $this->data['featureitem'] = json_decode($this->data['featureitem']);
        }

        if (is_array($this->data['featureitem']) && empty($this->data['featureitem'])) {
            unset($this->data['featureitem']);
        }

        if (is_string($this->data['pricelistitem'])) {
            $this->data['pricelistitem'] = json_decode($this->data['pricelistitem'], true);
        }

        if (is_array($this->data['pricelistitem']) && empty($this->data['pricelistitem'])) {
            unset($this->data['pricelistitem']);
        }

        if (is_string($this->data['stores'])) {
            $this->data['stores'] = json_decode($this->data['stores']);
        }

        if (is_array($this->data['stores']) && empty($this->data['stores'])) {
            unset($this->data['stores']);
        }
        $request_validate = clone $this->request;

        $request_validate->replace($this->data);

        $this->validate($request_validate,
            [
                'product_type_id' => 'required|integer',
                'category_id' => 'required|integer',
                'code' => 'required|max:64|regex:/^\S*$/u|regex:/^[a-zA-Z0-9.]+$/u|unique:products,code,'.$product_id,
                'image' => 'required',
                'uom_id'=> 'required|integer',
                'grade_group_id'=> 'required|integer',
                'short_name' => 'required',
                'display_name' => 'required',
                'release_date' => 'required|date',
                'featureitem' => 'required',
                'pricelistitem' => 'required',
                'stores' => 'required'
            ],
            [
                'product_type_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'product type']),
                'product_type_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'product type']),
                'category_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'category']),
                'category_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'category id']),
                'uom_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'uom']),
                'uom_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'uom']),
                'grade_group_id.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'grade group']),
                'grade_group_id.integer' => trans('messages.api.integer.app_error', ['Name' => 'grade group']),
                'short_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'short name']),
                'display_name.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'display name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
                'code.max' => trans('messages.api.max.product.app_error', ['Name' => 'code', 'Value' => '64']),
                'code.regex' => trans('messages.api.regex.code.product.app_error', ['Name' => 'code']),
                'image.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'image']),
                'image.image' => trans('messages.api.image.image.app_error', ['Name' => 'image']),
                'image.mimes' => trans('messages.api.image.mimes.app_error', ['Name' => 'image', 'Value' => 'jpeg,png,jpg,gif,svg']),
                'release_date.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'release date']),
                'release_date.date' => trans('messages.api.date.product.app_error', ['Name' => 'release date']),
                'featureitem.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'feature item']),
                'pricelistitem.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'price item']),
                'stores.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'product store']),
            ]);

        $this->data['id'] = $product_id;

        $product = $product->toModel($this->data);


        if ((isset($this->data['featureitem']) && is_array($this->data['featureitem']))
            || (isset($this->data['pricelistitem']) && is_array($this->data['pricelistitem']))
            || (isset($this->data['stores']) && is_array($this->data['stores']))
        ) {
            $featureitem = array_get($this->data, 'featureitem', []);
            $pricelistitem = array_get($this->data, 'pricelistitem', []);
            if(count($pricelistitem) >0) {
                foreach ($pricelistitem as $key=>$value) {
                    foreach ($pricelistitem[$key] as $key1 => $value) {
                        if($key1 == 'price_name' || $key1 == 'grade_name') {
                            unset($pricelistitem[$key][$key1]);
                        }
                    }
                    // unset($pricelistitem[$key]['price_name']);
                    // unset($pricelistitem[$key]['grade_name']);
                }
            } else {
                $pricelistitem = null;
            }
            $stores =  array_get($this->data, 'stores', []);
            $result = $product->updateProduct($featureitem, $pricelistitem, $stores);
        } else {
            $result = $product->updateProduct();
        }

        $this->output_json($result, 200);
    }

    public function delete()
    {
        $this->deleteRecord('Product');
    }

    public function getDetail(Product $product, $product_id, $factory_id)
    {

        $result = $product->getDetail($product_id, $factory_id);

        $this->output_json_client($result,200);
    }
    // check amount product exits kho
    public function barcodeCheck() {
        $client = new Client(); //GuzzleHttp\Client
        //'14','14.300600.09389',1,'','',0,'','','HOP',20,'55ed2e6f6402cbde5b28032fb5519de4'
        //["14","14.300600.09389",1,"","",0,"","","C\u00e1i",1,"08e85efe61e9648a4117cd69d7a760db"]
        $body = array("14","14.300600.09389",1,"","",0,"","","HOP",1,"08e85efe61e9648a4117cd69d7a760db");

        $data = $body[0] . $body[1] . $body[2] . $body[9] . '64F0F1368c68D97A7885B880F365C6B8';
        $code = md5($data);
        $body[10] = $code;

        $Nbody = json_encode($body, true);

        $response = $client->request('POST', 'http://dimi-api.prime.vn/eselling/PR_ESELLING_BARCODE_CHECK', [
                    'body' => $Nbody,
                    'headers'  => [
                        'Content-Type' => 'application/json',
                    ]
        ]);
         $this->output_json(['data' =>json_decode($response->getBody())], 200);
    }

    public function listBarcodeCheck() {

        $arrayBarcode = json_decode($this->data['dataCheckBarcode']);
        $client = new Client(); //GuzzleHttp\Client

        $data = $arrayBarcode[0] . $arrayBarcode[1] . $arrayBarcode[2]  . '64F0F1368c68D97A7885B880F365C6B8';
        $code = md5($data);
        $arrayBarcode[9] = $code;
        unset($arrayBarcode[10]);
        //        $data = $body[0] . $body[1] . $body[2]  '64F0F1368c68D97A7885B880F365C6B8';
//        $code = md5($data);
//        $body[10] = $code;

        $Nbody = json_encode($arrayBarcode, true);

        $response = $client->request('POST', 'http://dimi-api.prime.vn/eselling/PR_ESELLING_BARCODE_LIST', [
            'body' => $Nbody,
            'headers'  => [
                'Content-Type' => 'application/json',
            ]
        ]);
        $this->output_json(['data' =>json_decode($response->getBody())], 200);
    }

}
