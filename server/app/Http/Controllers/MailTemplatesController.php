<?php

namespace App\Http\Controllers;

use App\Models\MailTemplate;

class MailTemplatesController extends Controller
{
    public function index()
    {
        $mailTemplates = MailTemplate::select('id', 'name')->get();

        $this->output(['data'=>$mailTemplates],200);
    }

    public function getForm()
    {
        if(empty($this->data['id'])){
            $mailTemplates=MailTemplate::first();
            $this->output(['data'=>$mailTemplates], 200);
        }
        else {
            $mailTemplates = MailTemplate::where([['id', $this->data['id']]])->first();

            if($mailTemplates)
            {
                $this->output(['data'=>$mailTemplates], 200);
            }
            else
            {
                $this->output([MESSAGE=>trans('MailTemplate does not exist')], 404);
            }
        }
    }

    public function saveForm()
    {
        if($this->data['id'])
        {
            $this->validate($this->request,
                [
                    'subject' => 'required|unique:mail_templates,subject,'.$this->data['id'],
                    'content' => 'required'
                ]);
        }
        else
        {
            $this->validate($this->request,
                [
                    'name' => 'required|mail_templates:categories',
                    'content' => 'required'
                ]);
        }
        $this->saveRecord('MailTemplate');
    }

    public function delete()
    {
        $this->deleteRecord('MailTemplate');
    }
}