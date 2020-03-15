<?php

namespace App\Http\Controllers;

use App\Models\CategoriesPost;
use App\Models\Category;
use App\Models\Post;
use App\Models\TranslationContent;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    public function index()
    {
        if (!empty($this->data['type']) && $this->data['type'] == LIST_POST_FOR_MENU){
            $posts = Post::select('id', 'title as text')->get();
        }
        else $posts = Post::with(['user','categories'=>function($q){ $q->select('categories.id','categories.name'); }])->getDynamic();

        $this->output(['data'=>$posts],200);
    }

    public function getForm()
    {
        $post = Post::with('categories')->where([['id', $this->data['id']]])->first();

        if($post)
        {
            $this->output(['data'=>$post], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Post does not exist')], 404);
        }
    }

    public function saveForm()
    {
        $this->data['user_id'] = $this->curUser['id'];

        if($this->data['id']){
            $this->validate($this->request,
            [
                'title' => 'required|unique:posts,title,'.$this->data['id'],
            ]);
        }
        else{
            $this->validate($this->request,
            [
                'title' => 'required|unique:posts',
            ]);
        }

        $post = $this->saveRecord('Post',true);

        $post->categories()->detach();
        if(isset($this->data['category_ids'])){
            $post->categories()->attach(json_decode($this->data['category_ids'], true));
        }

        $this->output([MESSAGE=>trans('Post has been saved')], 200);
    }

    public function delete()
    {
        $this->validate($this->request,['id' => 'required']);
        if($post = Post::where([['id', $this->data['id']]])->first())
        {
            DB::transaction(function() use ($post) {
                try
                {
                    $post->categories()->detach();
                    $post->delete();
                    $this->deleteMultiLangTrans($this->data['id'], 'posts');
                }
                catch (\PDOException $e)
                {
                    throw $e;
                }
            });
            $this->output([MESSAGE=>trans('Post has been deleted')], 200);
        }
    }

    public function getListPostNotInCategory() {
        $joined = CategoriesPost::where('category_id', $this->request->get('category_id'))->pluck('post_id');

        $list = Post::whereNotIn('id', $joined)->select('id', 'title as text')->orderBy('order', 'asc')->get();
        $this->output(['data'=>$list],200);
    }

    public function updateOrder() {
        $data = $this->data;
        foreach ($data as $postId => $order) {
            $post = CategoriesPost::where(['id' => $postId])->first();
            if (!$post) {
                continue;
            }
            $post->order = $order;
            $post->save();
        }
        $this->output(['message' => 'Post has been updated'], 200);
    }


    /* -------------------------------------------------- API { -------------------------------------------------- */

    /**
     * GET: /post/category
     * Get all posts in all categories, group by category
     *
     */
    public function getPostInCategories()
    {
        $category = Category::where([['ui_home', true], ['active', true]])->select(['id', 'name', 'slug', 'description', 'image', 'ui_home_col', 'ui_home_style', 'ui_home_slide', 'ui_home_display', 'ui_home_limit'])
            ->with(['posts' => function($q) {
                return $q->where('active', true)->select(['posts.id', 'title', 'slug', 'description', 'content', 'image', 'view', 'user_id', 'posts.created_at']);
            }])->get()->toArray();

        $this->output(['data'=>$category], 200);
    }

    /**
     * GET: /post/category/detail
     * Get all posts in a category
     *
     * slug: The slug of the category
     *
     */
    public function getPostInCategory()
    {
        $catID = TranslationContent::select('table_id')->where(['table'=>'categories','table_field'=>'slug','trans'=>$this->data['slug']])->first();
        $catID = ($catID)?$catID->table_id:'';

        if($catID)
        {
            $category = Category::where(['id'=>$catID,'active'=>true])->first();
            $posts = Post::whereHas('categories', function($query) use ($catID){
                $query->where('categories.id', $catID);
            })->where(['active'=>ACTIVE_TRUE])->orderBy('created_at','desc')->paginate(2);
            $this->output(['category'=>$category, 'post'=>$posts],200);
        }

        $this->output([MESSAGE=>'Category not found'],400);
    }

    /**
     * GET: /post/detail
     * Get detail of a post by slug
     *
     * slug: The slug of the post
     *
     */
    public function getDetail() {
        $postID = TranslationContent::where(['table'=>'posts','table_field'=>'slug','trans'=>$this->data['slug'],'lang'=>$this->curLang])->first();

        if($postID)
        {
            $postID = $postID['table_id'];
            $post = Post::where(['id'=>$postID, 'active'=> ACTIVE_TRUE])->with('categories')->first();
            $this->output(['data'=>$post], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Post does not exist')], 404);
        }
    }
    /* -------------------------------------------------- API } -------------------------------------------------- */

}