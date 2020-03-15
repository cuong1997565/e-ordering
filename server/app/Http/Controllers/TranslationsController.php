<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use App\Models\TranslationOld;
use App\Models\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TranslationsController extends Controller
{
    public function index()
    {
        $trans = Translation::getDynamic();

        $this->output(['data'=>$trans],200);
    }

    public function getTrans()
    {
        $this->validate($this->request,
        [
            'lang' => 'required',
        ]);

        $lang = $this->data['lang'];

        $trans = Translation::select(['lang','key','trans'])->where('lang',$lang)->get();

        $this->output(['data'=>$trans],200);
    }

    public function getForm()
    {
        $trans = Translation::where([['id', $this->data['id']]])->first();

        if($trans)
        {
            $this->output(['data'=>$trans], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Contact does not exist')], 404);
        }
    }

    public function saveForm()
    {
        if(isset($this->data['id']))
        {
            // Update
            $this->validate($this->request,
                [
                    'key' => [
                        'required',
                        Rule::unique('translations')
                            ->where('id', '<>', $this->data['id'])
                            ->where('lang', $this->data['lang']),
                    ],

                    'lang' => 'required',
                ]);

            $trans = Translation::where([['id', $this->data['id']]])->first();
            $current_key = $trans->key;

            DB::transaction(function() use($trans, $current_key)
            {
                try
                {
                    foreach ($this->getConfigLang() as $value) {
                        if($this->data['lang'] == $value) {
                            $trans->fill($this->data)->save();
                        }
                        else {
                            Translation::where([['lang', $value],['key', $current_key]])
                                ->update(['key' => $this->data['key'], 'type'=>$this->data['type']]);
                        }
                    }
                }
                catch (\PDOException $e){throw $e;}
            });
            $this->output([MESSAGE=>trans('Translation has been updated')], 200);
        }
        else
        {
            // Create new
            $this->validate($this->request,
                [
                    'key' => [
                        'required',
                        Rule::unique('translations')->where('lang', $this->data['lang'])
                    ],
                    'lang' => 'required',
                ]);

            DB::transaction(function()
            {
                try
                {
                    foreach ($this->getConfigLang() as $value) {
                        if($this->data['lang'] == $value) {
                            Translation::create($this->data);
                        }
                        else {
                            $tmp = Translation::where([['lang', $value],['key', $this->data['key']]])->first();
                            if(!$tmp) Translation::create(['lang'=>$value, 'key'=>$this->data['key'], 'type'=>$this->data['type']]);
                        }
                    }
                }
                catch (\PDOException $e){throw $e;}
            });
            $this->output([MESSAGE=>trans('Translation has been created')],200);
        }
    }

    public function delete()
    {
        $this->validate($this->request,['id' => 'required']);

        if($record = Translation::where([['id', $this->data['id']]])->first())
        {
            DB::transaction(function() use ($record) {
                try
                {
                    Translation::where('key', $record->key)->delete();
                }
                catch (\PDOException $e)
                {
                    throw $e;
                }
            });
            $this->output([MESSAGE=>trans('Translation has been deleted')], 200);
        }
    }

    public function getDirContents($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }

    //auto gen key
    public function gen() {
        $old_trans = TranslationOld::select('key', 'lang', 'trans', 'type')->get()->toArray();

        foreach ($old_trans as $trans) {
            $tmp = Translation::where([['lang', $trans['lang']],['key',$trans['key']]])->first();
            if(!$tmp) Translation::create($trans);
            else {
                $data = $tmp->toArray();
                if(empty($data['trans'])) {
                    $tmp->update(['trans' => $trans['trans']]);
                }
            }
        }

        $listTrans = [];
        $regex = '/\{\{([ X]|)["|\'](.*?)([ X]|)[\'|"]([ X]|)(|)([ X]|)trans(.*)\}\}/';

        $files_admin = $this->getDirContents('../../client/admin/src/app');
        $files_top = $this->getDirContents('../../client/top/src/app');
        $files = array_merge($files_admin, $files_top);

        foreach ($files as $file) {
            $data = file_get_contents($file);
            preg_match_all($regex, $data, $matches);
            foreach ($matches[2] as $key) {
                $key = substr($key, 0, -1);
                if(!in_array($key, $listTrans)) $listTrans[] = $key;
            }
        }

        foreach ($this->getConfigLang() as $value) {
            foreach ($listTrans as $key){
                $tmp = Translation::where([['lang', $value],['key', $key]])->first();
                if(!$tmp) Translation::create(['lang'=>$value, 'key'=>$key, 'type'=>TRANSLATION_TYPE_TEXT]);
            }
        }

        $this->output(['data' => $listTrans],200);
    }

    public function diffFromServer() {
        // all translations from server
        $server_keys = TranslationOld::where('lang', $this->data['lang'])->pluck('key')->toArray();
        // all translations from this project
        $project_trans = Translation::select('id','key', 'trans', 'type')->where('lang', $this->data['lang'])
            ->orderBy('id','desc')
            ->get()->toArray();
        $data = [];
        foreach ($project_trans as $item) {
            if(!in_array($item['key'], $server_keys)) $data[] = $item;
        }

        $this->output(['data' => $data],200);
    }

    public function addToServer() {
        $data = Translation::select('key', 'trans', 'type')->whereIn('id', json_decode($this->data['data']))->get()->toArray();
        foreach ($data as $item) {
            $item['lang'] = $this->data['lang'];
            //check trans exist on server
            $checkLangServer = TranslationOld::where([['lang', $item['lang']],['key', $item['key']]])->first();

            if(!$checkLangServer){
                DB::transaction(function() use ($item) {
                    try
                    {
                        foreach ($this->getConfigLang() as $value) {
                            if($this->data['lang'] == $value) {
                                TranslationOld::create($item);
                            }
                            else {
                                $checkLangServerOtherLang = TranslationOld::where([['lang', $value],['key', $item['key']]])->first();
                                if(!$checkLangServerOtherLang){
                                    $transLocal = Translation::select('key', 'lang', 'trans', 'type')
                                        ->where([['lang', $value], ['key', $item['key']]])->first()->toArray();
                                    if($transLocal){
                                        TranslationOld::create($transLocal);
                                    }
                                }
                            }
                        }
                    }
                    catch (\PDOException $e)
                    {
                        throw $e;
                    }
                });
            }
        }

        $this->output([MESSAGE=>trans('Import success!')], 200);
    }

    public function duplicate() {
        if(!in_array($this->data['lang'], $this->getConfigLang())) {
            $this->output([MESSAGE=>trans('Language does not exist')], 400);
        }
        else {
            $data = Translation::select('key', 'type')->where('lang', $this->data['targetLang'])->get()->toArray();
            foreach ($data as $item) {
                $tmp = Translation::where([['lang', $this->data['lang']],['key', $item['key']]])->first();
                if(!$tmp) Translation::create(['key' => $item['key'], 'type' => $item['type'], 'lang' => $this->data['lang']]);
            }

            $this->output([MESSAGE=>trans('Copy success!')], 200);
        }
    }

    private function getConfigLang() {
        $langs = Lang::select('id','lang')->get()->toArray();
        $data = [];
        foreach ($langs as $lang) {
            $data[$lang['id']] = $lang['lang'];
        }
        return $data;
    }

}