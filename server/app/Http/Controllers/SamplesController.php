<?php

namespace App\Http\Controllers;

use App\Models\Sample;

class SamplesController extends Controller
{
    public function index()
    {
        $samples = Sample::orderBy('order')->getDynamic();

        $this->output(['data'=>$samples],200);
    }

    public function getForm()
    {
        $sample = Sample::with(['sample_items'])->where([['id', $this->data['id']]])->first();

        if($sample)
        {
            $this->output(['data'=>$sample], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Sample does not exist')], 404);
        }
    }

    public function saveForm()
    {
        $this->validate($this->request,
        [
            'name' => 'required',
            'description' => 'required',
            'content' => 'required',
            'sample_type_id' => 'required',
            'sample_items.*.name'=>'required'
        ]);

        $this->saveRecord('Sample');
    }
    
    public function delete()
    {
        $this->deleteRecord('Sample');
    }
}