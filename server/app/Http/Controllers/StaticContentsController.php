<?php

namespace App\Http\Controllers;

use App\Models\StaticContent;
use Illuminate\Support\Facades\DB;

class StaticContentsController extends Controller
{
    public function init() {
        $request = json_decode($this->data['code']);
        $codes = [];
        foreach($request as $item) {
            $codes[] = $item->code;
        }
        $find = StaticContent::whereIn('code', $codes)->select('code')->get()->toArray();
        $findResult = array_column($find, 'code');

        $diff = array_diff($codes, $findResult);
        if (count($diff)) {
            DB::beginTransaction();
            try {
                foreach($request as $item) {
                    if (in_array($item->code, $diff)) {
                        StaticContent::create(['code' => $item->code, 'description' => $item->description]);    
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
            DB::commit();
        }
        $staticContents = StaticContent::select(['code', 'description', 'content', 'active'])->getDynamic();
        $this->output(['data' => $staticContents], 200);
    }

    public function index() {
        $staticContents = StaticContent::select(['code', 'description', 'content', 'active'])->getDynamic();
        $this->output(['data' => $staticContents], 200);
    }

    public function getForm() {
        $staticContents = StaticContent::where([['id', $this->data['id']]])->first();

        if($staticContents)
        {
            $this->output(['data' => $staticContents], 200);
        }
        else
        {
            $this->output([MESSAGE => trans('StaticContent does not exist')], 404);
        }
    }

    public function save() {
        $request = json_decode($this->data['items'], true);
        // dd(1);
        DB::beginTransaction();
        try {
            foreach($request as $record) {
                StaticContent::where('code', $record['code'])->update($record);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->output([MESSAGE=>'Data has not been saved'], 200);
        }
        DB::commit();
        $this->output([MESSAGE=>'Data has been saved'], 200);
    }

    public function delete()
    {
        $this->deleteRecord('StaticContent');
    }
}
