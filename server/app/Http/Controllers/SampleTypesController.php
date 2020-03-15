<?php

namespace App\Http\Controllers;

use App\Models\SampleType;

class SampleTypesController extends Controller
{
    public function index()
    {
        $sampleTypes = SampleType::getDynamic();
        $this->output(['data'=>$sampleTypes],200);
    }

    public function getForm()
    {
        $sampleType = SampleType::where([['id', $this->data['id']]])->first();

        if($sampleType)
        {
            $this->output(['data'=>$sampleType], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Sample type does not exist')], 404);
        }
    }

    public function saveForm()
    {
        $this->validate($this->request,
        [
            'name' => 'required',
        ]);

        $this->saveRecord('SampleType');
    }

    public function delete()
    {
        $this->deleteRecord('SampleType');
    }
}