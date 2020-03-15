<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
class AppModel extends Model
{
    protected static function VirtualColumn() {
        return [];
    }

    protected static function boot()
    {
        parent::boot();

        self::saving(function ($model)
        {
            /* ---------- Upload files { ---------- */
            if(isset($model->files))
            {
                foreach($model->files as $field => $option)
                {
                    $files = app('request')->file($field);

                    if(!$files)
                    {
                        unset($model->$field);
                        continue;
                    }

                    if($files && !is_array($files))
                    {
                        $files = [$files]; // If file not in array, convert it to array, so we can easily manipulation on it as below
                    }

                    // Save files to storage
                    if($files)
                    {
                        array_walk($files, function ($file) use ($model,$field,$option)
                        {
                            // Check validation
                            $ext = $file->getClientOriginalExtension();
                            $arrExt = explode('|',$option['ext']);
                            if(!in_array($ext,$arrExt))
                            {
                                echo 'Hack detected.'; die;
                            }

                            // Store in suitable folder
                            $folder = $model->getMorphClass();
                            $folder = str_replace('App\\Models\\','',$folder);

                            $fileName = Storage::disk(env('STORAGE_DISK'))->putFileAs($folder, $file,substr($file->hashName(),0,strpos($file->hashName(),'.')).'.'.$ext);
                            $model->$field = $fileName;

                            // Remove old file if upload new one
                            $original = $model->getOriginal();
                            if(!empty($original[$field]))
                            {
                                Storage::disk(env('STORAGE_DISK'))->delete($original[$field]);
                            }
                        });
                    }
                }
            }
            /* ---------- Upload files } ---------- */
        });

        self::deleted(function ($model)
        {
            /* ---------- Delete files { ---------- */
            if(isset($model->files))
            {
                foreach ($model->files as $field => $option)
                {
                    if(isset($model->$field) && !empty($model->$field))
                    {
                        Storage::disk(env('STORAGE_DISK'))->delete($model->$field);
                    }
                }
            }
            /* ---------- Delete files } ---------- */
        });
    }

    public function scopeGetDynamic($query)
    {
        $curTable = $this->getTable();
        $fields = array_diff(\Schema::getColumnListing($curTable),$this->getHidden());

        /* ---------- Set limit { ---------- */
        if(isset($_GET['limit']) && is_numeric($_GET['limit']))
        {
            $query->limit($_GET['limit']);
        }

        /* ---------- Set limit } ---------- */

        /* ---------- Set joins { ---------- */
        $isJoin = false;
        if(isset($query->getModel()->belongTo))
        {
            // Add fields with prefix table
            $exFields = $fields;
            array_walk($exFields, function(&$value) use ($curTable) {$value = $curTable.':'.$value ;});
            $fields = array_merge($fields,$exFields);

            foreach($query->getModel()->belongTo as $strBelongTo)
            {
                $isJoin = true;

                // Do the join
                $bQuery = $query->getModel()->$strBelongTo();
                $bModel = $bQuery->getModel();
                $fKey = $bQuery->getForeignKey();
                $key = $bQuery->getOwnerKey();
                $curTableName = $query->getModel()->getTable();
                $joinTable = $bModel->getTable();

                $query->with($strBelongTo)->leftJoin($joinTable,"$joinTable.$key",'=',"$curTableName.$fKey")->select(["$curTableName.*"]);

                // Add fields in belong to tables
                $bFields = array_diff(\Schema::getColumnListing($joinTable),$bModel->getHidden());
                array_walk($bFields, function(&$value) use ($joinTable) {$value = $joinTable.':'.$value;});
                $fields = array_merge($fields,$bFields);
            }
        }

        /* ---------- Set joins } ---------- */

        /* ---------- Set select { ---------- */
        $select = [];
        if(isset($_GET['select']))
        {
            $select = explode(',', $_GET['select']);

            // Add prefix
            if($isJoin)
            {
                $newSelect = [];
                foreach($select as $field)
                {
                    $field = str_replace(':','.',$field);
                    if(strpos($field,'.') === false)
                    {
                        $field = $curTable.'.'.$field;
                    }
                    array_push($newSelect,$field);
                }
                $select = $newSelect;
            }

            // Separate current table & join table (current => use select, join => use with)
            $arrSelect = [];
            foreach($select as $item)
            {
                $arr = explode('.',$item);
                $table = $arr[0];
                $field = $arr[1];

                if(!isset($arrSelect[$table]))
                {
                    $arrSelect[$table] = [];
                }

                array_push($arrSelect[$table],$field);
            }

            $arrWith = [];
            foreach($arrSelect as $table => $fields)
            {
                if($table == $curTable) // Using Select
                {
                    $select = [];
                    foreach($fields as $field)
                    {
                        array_push($select,$table.'.'.$field);
                    }

                    $query->select($select);
                }
                else // Using  With
                {
                    $select = str_singular($table).':';
                    foreach($fields as $field)
                    {
                        $select .= $field.',';
                    }
                    $select = substr($select,0,-1);

                    array_push($arrWith,$select);
                }
            }

            $query->with($arrWith);
        }
        /* ---------- Set select } ---------- */

        /* ---------- Set where { ---------- */
        foreach($fields as $field)
        {
            if(isset($_GET[$field]))
            {
                $value = $_GET[$field];
                $field = str_replace(':','.',$field);

                // If join => need to add table prefix
                if($isJoin && strpos($field,'.') === false)
                {
                    $field = $curTable.'.'.$field;
                }

                // Get operator
                $op = substr($value,0,2);
                if(in_array($op,array('<>','>=','<=')))
                {
                    $value = substr($value,2);
                    $query->where($field,$op,$value);
                }
                else
                {
                    $op = substr($value,0,1);
                    if(in_array($op,array('>','<')))
                    {
                        $value = substr($value,1);
                        $query->where($field,$op,$value);
                    }
                    else
                    {
                        $op = null;

                        $specialSearch = false;
                        /* ---------- Special query { ---------- */
                        // like
                        $firstChar = substr($value, 0 ,1);
                        $lastChar =  substr($value, -1 ,1);

                        $searchLike = false;

                        if($firstChar == '*')
                        {
                            $searchLike = true;
                            $value = substr($value, 1);
                            $firstChar = '%';
                        }
                        else
                        {
                            $firstChar = '';
                        }

                        if($lastChar == '*')
                        {
                            $searchLike = true;
                            $value = substr($value, 0, strlen($value)-1);
                            $lastChar = '%';
                        }
                        else
                        {
                            $lastChar = '';
                        }

                        if($searchLike)
                        {
                            $specialSearch = true;
                            $query->where($field,'like',"$firstChar$value$lastChar");
                        }
                        /* ---------- Special query } ---------- */

                        if(!$specialSearch)
                        {
                            // Suport search  in ()
                            $arrValue = explode('||',$value);
                            if($arrValue > 1)
                            {
                                $query->whereIn($field,$arrValue);
                            }
                            else
                            {
                                // Normal case => not use $op
                                $query->where($field,$value);
                            }
                        }
                    }
                }
            }
        }

        /* ---------- Set where } ---------- */

        /* ---------- Set order { ---------- */
        if(isset($_GET['sort']))
        {
            $sort = $_GET['sort'];
            $sort = str_replace(':','.',$sort);
            $direction = 'asc';
            if(isset($_GET['direction']) && ($_GET['direction'] == 'asc' || $_GET['direction'] == 'desc'))
            {
                $direction = $_GET['direction'];
            }

            $query->orderBy($sort, $direction);
        }

        /* ---------- Set order } ---------- */

        if(isset($_GET['paging']) && $_GET['paging'])
        {
            if(isset($_GET['limit']) && is_numeric($_GET['limit']))
            {

                return $query->paginate($_GET['limit']);
            }
            else
            {
                return $query->paginate();
            }
        }

        else
        {
            return $query->get();
        }
    }

    public function toInstanceArray() {
        $data = [];
        foreach ($this->fillable as $key) {
            if ($this->$key !== 0) {
                $data[$key] = $this->$key;
            }
            if ($this->$key) {
                $data[$key] = $this->$key;
            }
        }
        return $data;
    }

    public function toModel($data) {
        foreach ($this->fillable as $key) {
            if (isset($data[$key])) {
                $this->$key = $data[$key];
            }
        }
        return $this;
    }

    protected static function output($data,$statusCode)
    {
        response()->json($data, $statusCode)->send(); die;
    }

    public static function isAdmin() {
        $request = app('request');
        $curUser = $request->curUser;
        return $curUser && $curUser->group === GROUP_ADMIN;
    }

    public static function isSaleFactory() {
        $request = app('request');
        $curUser = $request->curUser;
        return $curUser && $curUser->group === GROUP_SALE_FACTORY;
    }
}
