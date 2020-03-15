<?php

namespace App\Http\Controllers;

use App\Models\CategoriesPost;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CategoryPostsController extends Controller
{

    public function index() {
        $post = Post::join('categories_posts', 'posts.id', '=', 'categories_posts.post_id')
            ->where('categories_posts.category_id', $this->request->get('category_id'))->orderBy('categories_posts.order', 'asc')->getDynamic();

        $this->output(['data'=>$post],200);
    }

    public function delete()
    {
        $this->deleteRecord('CategoriesPost');
    }

    public function add() {

        DB::beginTransaction();
        try {
            CategoriesPost::create($this->request->all());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'false', 'msg' => 'Data has not been saved'], 500);
        }
        DB::commit();
        return response()->json(['status' => 'true', 'msg' => 'Data has been saved'], 200);
    }
}
