<?php

namespace App\Http\Controllers;

use App\App\AuthorizationRepository;
use App\Http\Context\Context;
use App\Models\Error;
use App\Providers\Logger\Facade\AppLogger;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TranslationContent;

class Controller extends BaseController
{
    var $authorization;
    var $curUser; // The current login user
    var $curMember; // The current login member
    var $curLang; // The current client language
    var $request;
    var $data; // The request data from POST data

    public function __construct(Request $request, Context $context, AuthorizationRepository $authorization)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Expose-Headers: ETag');

        $this->request = $request;
        $this->curUser = $request->curUser;
        $this->curLang = $request->curLang;
//        Config::set('app.timezone', 'Asia/Ho_Chi_Minh');
        $this->authorization = $authorization;
        $this->customDataTransform();
    }

    /**
     *  This function used just to deal with the problem in json format
     */
    private function customDataTransform()
    {
        $data = $this->request->input();

        unset($data['_method']);
        unset($data['token']);

        /* Convert json to array : used for validation & save hasMany */
        $data = $this->convertHasManyData($data);

        /* Transform null value */
        foreach($data as $key => $value)
        {
            if($value == '')
            {
                $data[$key] = null;
            }
        }

        $this->request->replace($data);
        $this->data = $data;
    }

    /**
     *  Convert all hasMany request data from json to array => It's use for validation
     */
    private function convertHasManyData($data)
    {
        $hasMany = ['sample_items']; // Need to auto get all of this in the system

        foreach($hasMany as $item)
        {
            if(isset($data[$item]))
            {
                $data[$item] = json_decode($data[$item],true);
            }
        }

        return $data;
    }


    /**
     *  The global save function
     */
    public function saveRecord($modelName,$returnModel=false)
    {
        if (empty($this->data)) {
            $this->output([MESSAGE=>trans('Please enter data before send')], 400);
        }

        $this->formConfirm();
        $model = MODEL_PATH . $modelName;

        if (isset ($this->data['id']))
        {
            $model = $model::where(['id'=>$this->data['id']])->first();

            DB::transaction(function() use ($model)
            {
                try
                {
                    $model->fill($this->data)->save();
                    $this->saveModel = $model;
                    $this->updateHasMany($model);
                    $this->updateTranslationContent($model);
                }
                catch (\PDOException $e){throw $e;}
            });

            if($returnModel)
            {
                return $this->saveModel;
            }
            else
            {
                $this->output([MESSAGE => trans($modelName . ' has been updated')], 200);
            }
        }
        else
        {
            DB::transaction(function() use ($model)
            {
                try
                {
                    $model = $model::create($this->data);
                    $this->saveModel = $model;
                    $this->updateHasMany($model);
                    $this->updateTranslationContent($model);
                }
                catch (\PDOException $e){throw $e;}
            });

            if($returnModel)
            {
                return $this->saveModel;
            }
            else
            {
                $this->output([MESSAGE=>trans($modelName.' has been created')],200);
            }
        }
    }

    /**
     *   Auto update the content in the multiple language fields in to the translation table.
     */
    public function updateTranslationContent($model)
    {
        $tableName = $model->getTable();
        TranslationContent::where([['table', $tableName], ['table_id', $model->id]])->delete();

        if(isset($model->multipleLangFields))
        {
            $fields = $model->multipleLangFields;
            $model = $model->toArray();

            foreach ($fields as $field){
                if(isset($model[$field])) {
                    $data = json_decode($model[$field]);

                    if($data)
                    {
                        foreach ($data as $k => $v) {
                            TranslationContent::create([
                                'table' => $tableName,
                                'table_id' => $model['id'],
                                'table_field' => $field,
                                'lang' => $k,
                                'trans' => strip_tags($v)
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function deleteTranslationContent($model)
    {
        $tableName = $model->getTable();
        TranslationContent::where([['table', $tableName], ['table_id', $model->id]])->delete();
    }

    /**
     *  This function is used to auto remove/insert new the hasMany items
     */
    function updateHasMany($model)
    {
        if(isset($model->hasMany))
        {
            foreach ($model->hasMany as $strModel)
            {
                if (method_exists($model, $strModel))
                {
                    // Delete old related
                    $model->$strModel()->delete();

                    // Insert new related
                    if (isset($this->data[$strModel])) {
                        //$relData = json_decode($this->data[$strModel], true);
                        $relData = $this->data[$strModel];

                        $foreignKey = $model->$strModel()->getForeignKeyName();
                        $foreignKey = str_replace($strModel . '.', '', $foreignKey);

                        /* --- Set foreign key to the data { --- */
                        $dataWithID = [];
                        $dataWithOutID = [];
                        foreach ($relData as $item) {
                            if (!isset($item[$foreignKey])) {
                                $item[$foreignKey] = $model->id;
                            }

                            if (empty($item['id'])) {
                                array_push($dataWithOutID, $item);
                            } else {
                                array_push($dataWithID, $item);
                            }
                        }
                        /* --- Set foreign key to the data } --- */

                        $model->$strModel()->insert($dataWithID);
                        $model->$strModel()->insert($dataWithOutID);
                        $model->$strModel()->touch();
                    }
                }
            }
        }
    }

    /**
     *  Save multiple records
     */
    public function saveMany($modelName)
    {
        if (empty($this->data)) {
            $this->output([MESSAGE=>trans('Please enter data before send')], 400);
        }

        $this->formConfirm();
        $model = MODEL_PATH . $modelName;
        if (isset ($this->data['id']))
        {
            $record = $model::where(['id'=>$this->data['id']])->first();

            if ($record->fill($this->data)->save())
            {
                $this->output([MESSAGE=>trans($modelName.' has been updated')], 200);
            }
            else{
                $this->output([MESSAGE=>trans($modelName.' cannot be updated')], 400);
            }
        }
        else
        {
            $array_data = $this->data;
            DB::beginTransaction();
            foreach ($array_data as $data) {
                $model::create($data);
            }
            DB::commit();
            $this->output([MESSAGE=>trans($modelName.' has been saved')], 200);
        }
    }

    /**
     *  The global delete function
     */
    public function deleteRecord($modelName)
    {
        $model = MODEL_PATH . $modelName;

        $this->validate($this->request,['id' => 'required']);

        if($record = $model::where([['id', $this->data['id']]])->first())
        {
            DB::transaction(function() use ($record) {
                try
                {
                    $record->delete();
                }
                catch (\PDOException $e)
                {
                    throw $e;
                }
            });
            $this->output([MESSAGE=>trans($modelName.' has been deleted')], 200);
        }
    }

    public function output($data,$statusCode)
    {
        response()->json($data, $statusCode)->send(); die;
    }

    public function output_json($data, $statusCode) {
        app('translator')->setLocale('en');
        header('X-Request-ID: '. app('uuid'));
        if ((is_object($data) && get_class($data) === Error::class)) {
            $statusCode = $data->StatusCode;
            if (is_array($data->Params)) {
                $data->Message = trans('messages.'.$data->Id, $data->Params);
            } else {
                $data->Message = trans('messages.'.$data->Id);
            }
            AppLogger::LogError($data);
            AppLogger::WriteLogError();
            response()->json($data, $statusCode)->send(); die;
        }
        response()->json($data, $statusCode)->send(); die;
    }

    public function output_json_client($data, $statusCode) {
        app('translator')->setLocale('en');
        header('X-Request-ID: '. app('uuid'));
        if ((is_object($data) && get_class($data) === Error::class)) {
            $statusCode = $data->StatusCode;
            if (is_array($data->Params)) {
                $data->Message = trans('messages.'.$data->Id, $data->Params);
            } else {
                $data->Message = trans('messages.'.$data->Id);
            }
            response()->json($data, $statusCode)->send(); die;
        }
        response()->json(['data' => $data], $statusCode)->send(); die;
    }

    public function output_status_ok($data) {
        $statusCode = 200;
        header('X-Request-ID: '. app('uuid'));
        app('translator')->setLocale('en');
        if ((is_object($data) && get_class($data) === Error::class)) {
            $statusCode = $data->StatusCode;
            $data->Message = trans('messages.'.$data->Id);
            AppLogger::LogError($data);
            AppLogger::WriteLogError();
            response()->json($data, $statusCode)->send(); die;
        }
        response()->json(['status' => 'OK'], $statusCode)->send(); die;
    }
    public function output_status_fail($data) {
        $statusCode = 400;
        header('X-Request-ID: '. app('uuid'));
        app('translator')->setLocale('en');
        if ((is_object($data) && get_class($data) === Error::class)) {
            $statusCode = $data->StatusCode;
            $data->Message = trans('messages.'.$data->Id);
            AppLogger::LogError($data);
            AppLogger::WriteLogError();
            response()->json($data, $statusCode)->send(); die;
        }
        response()->json(['status' => 'Not Ok'], $statusCode)->send(); die;
    }
    /**
     *  This function run after the validation and skip the save method
     */
    public function formConfirm()
    {
        if(isset($this->data['form_confirm']) && $this->data['form_confirm'] != 0)
        {
            response()->json(['confirm'=>true], 200)->send(); die;
        }
    }
}
