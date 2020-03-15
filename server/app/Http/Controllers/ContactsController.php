<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactsController extends Controller
{
    public function index()
    {
        $contacts = Contact::getDynamic();

        $this->output(['data'=>$contacts],200);
    }

    public function getForm()
    {
        $contacts = Contact::where([['id', $this->data['id']]])->first();

        if($contacts)
        {
            $this->output(['data'=>$contacts], 200);
        }
        else
        {
            $this->output([MESSAGE=>trans('Contact does not exist')], 404);
        }
    }

    public function saveForm()
    {
        $this->validate($this->request,
            [
                'name' => 'required',
                'phone_number' => 'required|unique:contacts',
                'email' => 'required|email|unique:contacts',
                'content' => 'required',
            ]);

        $this->saveRecord('Contact');
    }

    public function delete()
    {
        $this->deleteRecord('Contact');
    }
}