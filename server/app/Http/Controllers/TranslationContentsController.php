<?php

namespace App\Http\Controllers;

use App\Models\TranslationContent;

class TranslationContentsController extends Controller
{
    public function search()
    {
        $this->output(['data'=>''],200);
    }
}