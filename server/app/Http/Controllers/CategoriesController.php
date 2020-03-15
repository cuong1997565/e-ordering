<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Providers\Logger\Facade\AppLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Log\LogManager;
class CategoriesController extends Controller
{
    public function index()
    {
        $category = Category::getDynamic();

        $this->output_json(['data' => $category], 200);
    }

    public function getCategoryAboutProduct() {
        $category = Category::with(['category_level_tow' => function($c2){
            $c2->with(['category_level_three' => function($c3) {
                $c3->with(['category_level_four' => function($c4) {
                    $c4->select('id','name','parent_id','active')->where('active', ACTIVE_TRUE);
                }])->select('id','name','parent_id','active')->where('active', ACTIVE_TRUE);
            }])->select('id','name','parent_id','active')
                ->where('active', ACTIVE_TRUE);
        }])->select('id','name', 'parent_id','active')
            ->where('active', ACTIVE_TRUE)
            ->where('parent_id', 0)->getDynamic();
        $this->output_json(['data' => $category], 200);
    }

    //api client category
    public function getClientCategory()
    {
        $category = Category::with([
            'categorys' => function($c2) {
                $c2->with(['category_level_three' => function($c3){
                    $c3->with(['category_level_four' => function($c4) {
                        $c4->select('id','name','parent_id','active')->where('active', ACTIVE_TRUE);
                    }])->select('id','name','parent_id','active')->where('active', ACTIVE_TRUE);
                }])->select(['id', 'name','parent_id','active'])
                    ->where('active',ACTIVE_TRUE)->orderBy('name','asc');
            }
        ])->where('parent_id',0)
            ->where('active',ACTIVE_TRUE)
            ->select('id','name','parent_id','active')->orderBy('name','asc')->getDynamic();
        $this->output_json(['data' => $category], 200);
    }

    //api client category level 2
    public function getClientParentCategory()
    {
        $category = Category::where('parent_id','!=',0)->getDynamic();
        $this->output_json(['data' => $category], 200);
    }

    public function createCategory(Category $category)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'code' => 'required|unique:categories,code',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
            ]);
        $category = $category->toModel($this->data);


        $result = $category->createCategory();

        $this->output_json($result, 200);
    }

    public function updateCategory($category_id, Category $category)
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'code' => 'required|unique:categories,code,'. $category_id,
//                'order' => 'required',
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name']),
                'code.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'code']),
                'code.unique' => trans('messages.api.exist.app_error', ['Name' => 'code']),
            ]);

        $this->data['id'] = $category_id;

        $category = $category->toModel($this->data);

        $result = $category->updateCategory();

        $this->output_json($result, 200);
    }

    public function searchCategoriesByName(Category $category)
    {
        $this->validate($this->request,
            [
                'name' => 'required'
            ],
            [
                'name.required' => trans('messages.api.invalid_url_param.app_error',['Name' => 'name'])
            ]);

        $result = $category->searchCategoriesByName($this->data['name']);

        $this->output_json($result,200);
    }

    public function getCategoryByName($category_name, Category $category)
    {
        $result = $category->getCategoryByName($category_name);
        return $this->output_json($result, 200);
    }

    public function delete()
    {
        $this->deleteRecord('Category');
    }
}
