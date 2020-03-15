<?php
namespace App\Models;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\DB;

class Menu extends AppModel
{
    use NodeTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'type', 'type_internal' , 'link', 'bind_post', 'order', 'open_tab', 'active'
    ];

    public static function scopeGetInternalLink() {
        $data = self::select('menus.*', 'categories.slug as cate_slug', 'posts.slug as post_slug')
            ->leftJoin('categories', function($join) {
                $join->on('menus.type', '=', DB::raw(MENU_TYPE_INTERNAL))
                    ->on('menus.type_internal', '=', DB::raw(MENU_TYPE_INTERNAL_CATEGORY))
                    ->on('categories.id', '=', 'menus.link');
            })
            ->leftJoin('posts', function($join) {
                $join->on('menus.type', '=', DB::raw(MENU_TYPE_INTERNAL))
                    ->on('menus.type_internal', '=', DB::raw(MENU_TYPE_INTERNAL_POST))
                    ->on('posts.id', '=', 'menus.link');
            })
            ->where('menus.active', '=', ACTIVE_TRUE)
            ->orderBy('order', 'asc')
            ->get();

        return $data;
    }
}