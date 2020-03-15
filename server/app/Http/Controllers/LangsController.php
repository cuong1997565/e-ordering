<?php

namespace App\Http\Controllers;

use App\Models\Lang;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;

class LangsController extends Controller
{
    public function index()
    {
        $lang = Lang::getDynamic();

        $this->output(['data'=>$lang],200);
    }

    public function getForm()
    {
        $lang = Lang::where([['id', $this->data['id']]])->first();

        if($lang)
        {
            $this->output(['data'=>$lang], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Lang does not exist')], 404);
        }
    }

    public function saveForm()
    {
        if($this->data['id'])
        {
            $this->validate($this->request,
                [
                    'lang' => 'required|unique:langs,lang,'.$this->data['id'],
                ]);

            $this->saveRecord('Lang');
        }
        else
        {
            $this->validate($this->request,
                [
                    'lang' => 'required|unique:langs',
                ]);

            DB::transaction(function()
            {
                try
                {
                    Lang::create($this->data);
                    $data = Translation::select('key', 'type')->where('lang', config('base.default_lang'))->get()->toArray();
                    foreach ($data as $item) {
                        $tmp = Translation::where([['lang', $this->data['lang']],['key', $item['key']]])->first();
                        if(!$tmp) Translation::create(['key' => $item['key'], 'type' => $item['type'], 'lang' => $this->data['lang']]);
                    }
                }
                catch (\PDOException $e){throw $e;}
            });
            $this->output([MESSAGE=>trans('Lang has been created')],200);
        }

    }

    public function delete()
    {
        $this->deleteRecord('Lang');
    }

    public function getLangs()
    {
        $lang = Lang::select('id','lang')->get();

        $this->output(['data' => $lang], 200);
    }
}
