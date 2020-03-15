<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Post;
use App\Models\StaticContent;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

class CommonsController extends Controller
{
    /**
     * GET: /static-data
     * Get all static data in the system
     *
     */
    public function staticData()
    {
//        $staticData =
//        [
//            'env' => [], // Server configuration
//            'trans' => [], // Language translation
//            'profile' => [], // Member profile (if system has member module)
//            'menu' => [], // Top menu
//            'content' => [], // Static content
//            'list' => [], // List of static category with post
//            'post' => [], // List of static post
//        ];
//
//        // env
//        $staticData['env'] = [
//            'BASE_SOCKET' => env('BASE_SOCKET'),
//        ];
//
//        // trans
//        $staticData['trans'] = Translation::select(['lang','key','trans'])->where('lang',$this->curLang)->get();
//
//        // profile
//        $staticData['profile'] = $this->curMember;
//
//        // menu
//        $staticData['menu'] = Menu::scopeGetInternalLink()->toTree();
//
//        // content
//        $staticData['content'] = StaticContent::select(['code', 'description', 'content', 'active'])->getDynamic();
//
//        // list
//        $staticData['list'] = Post::getStaticList();
//
//        // post
//        $staticData['post'] = Post::getStaticPost();
//
//        $this->output($staticData, 200);
    }

    public function active()
    {
        $this->validate($this->request, [
            'model' => 'required',
            'id' => 'required',
        ]);

        $model = MODEL_PATH . $this->data['model'];
        $model = $model::where(['id'=>$this->data['id']])->first();
        $model->active = !$model->active;

        if($model->save()) {
            $this->output([MESSAGE=>trans('Active have been switched')],200);
        }
        else {
            $this->output([MESSAGE=>trans('Cannot switch')],400);
        }
    }


    /**
     *  Server code & client code using same env setting
     */
    public function env()
    {
        $env = [
            'BASE_SOCKET' => env('BASE_SOCKET'),
        ];

        $this->output($env, 200);
    }

    /**
     *  Handle the error in angular client & log in to the server
     */
    public function saveError()
    {
        if (!empty($_POST)) {
            $msg = "\n -----------------------------------------";
            foreach ($_POST as $k => $v) {
                $key = htmlentities(strip_tags(urldecode($k)));
                $value = htmlentities(strip_tags(urldecode($v)));
                $msg .= "\n " . $key . ": " . $value;
            }
            $msg .= "\n ----------------------------------------- \n \n ";

            Helpers::saveLog($msg, 'client_errors');
            $this->output(['status' => 200,MESSAGE=>trans('Success')], 200);
        }
        $this->output(['status' => 500,MESSAGE=>trans('Error')], 500);
    }
    
    public function updateOrder() {
        $data = $this->data;
        if (!$data || !$data['table'] || !$data['data']) {
            $this->output([MESSAGE=>trans('Cannot sorting')],400);
        }
        $table = $data['table'];
        $dataSorting = json_decode($data['data'], true);
        foreach ($dataSorting as $item) {
            $checkItem = DB::table($table)->where('id', $item['id']);
            if (!$checkItem) {
                $this->output([MESSAGE=>trans('Cannot sorting')],400);
            }
            $checkItem->update(['order' => $item['order']]);
        }
        $this->output(['status' => 200,MESSAGE=>trans('Success')], 200);
    }
}
