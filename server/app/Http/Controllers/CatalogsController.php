<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CatalogsController extends Controller
{
    public function getListFile()
    {
        $files = Catalog::getDynamic();
        $this->output(['data'=>$files],200);
    }

    public function saveFileForm()
    {
        $this->validate($this->request,
            [
                'file' => 'required',
            ],
            [
                'file.required' => trans('messages.api.invalid_url_param.app_error', ['Name' => 'file']),
            ]);
        $this->saveRecord('Catalog');
    }

    public function delete()
    {
        $this->deleteRecord('Catalog');
    }

    public function downFile() {
        $filePath = $this->data['url'];
        $name = $this->data['name'];
        $file_url = 'files/' . urldecode($filePath);
        $pdfname = basename ($file_url);
        $path = pathinfo($pdfname);
        if($path['extension'] === 'pdf'){
            header('Content-Type: application/pdf');
        }
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=".$name);
        readfile($file_url);
    }


}
