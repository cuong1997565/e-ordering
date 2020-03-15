<?php
namespace App\Models;

class Post extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'view', 'image', 'order', 'user_id', 'active', 'content', 'sys'
    ];

    public $multipleLangFields =
    [
        'title','slug','description','content'
    ];

    public function categories(){
        return $this->belongsToMany('App\Models\Category', 'categories_posts');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the static category with posts
     *
     */
    public static function getStaticList($arrCat = [])
    {
        if(empty($arrCat))
        {
            $arrCat = config('category.sys');
        }

        $category = Category::whereIn('id',$arrCat)->where(['active'=>true])->select(['id', 'name', 'slug', 'description', 'image', 'ui_home_col', 'ui_home_style', 'ui_home_slide', 'ui_home_display', 'ui_home_limit'])
            ->with(['posts' => function($q) {
                return $q->where('active', true)->select(['posts.id', 'title', 'slug', 'description', 'image', 'view', 'posts.created_at']);
            }])->get()->toArray();

        // Transform data for easy use
        $data = [];
        foreach($category as $item)
        {
            $posts = $item['posts'];
            unset($item['posts']);

            foreach($posts as $i => $post)
            {
                $posts[$i]['pivot_order'] = $posts[$i]['pivot']['order'];
                unset($posts[$i]['pivot']);
            }

            $data[$item['id']] = [];
            $data[$item['id']]['category'] = $item;
            $data[$item['id']]['posts'] = $posts;

        }

        return $data;
    }

    /**
     * Get the static posts
     *
     */
    public static function getStaticPost($arrPost=[])
    {
        if(empty($arrPost))
        {
            $arrPost = config('post.sys');
        }

        $posts = Post::whereIn('id',$arrPost)->where(['active'=>true])->select(['id','title','slug','description','image'])->get();

        $data = [];
        foreach($posts as $item)
        {
            $data[$item['id']] = [];
            $data[$item['id']] = $item;
        }

        return $data;
    }

}