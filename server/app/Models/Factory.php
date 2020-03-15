<?php
namespace App\Models;

use App\Store\FactoryStore;

class Factory extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'code', 'email','phone', 'address', 'active'
    ];

    public function getAll()
    {
        $factoryList = (new FactoryStore())->getAll();

        return $factoryList;
    }

    public function createFactory()
    {
        $this->id = null;

        $result = (new FactoryStore())->save($this);

        return $result;
    }

    public function updateFactory()
    {
        $result = (new FactoryStore())->update($this);

        return $result;
    }


    public function getFactory($factory_id)
    {
        return (new FactoryStore())->get($factory_id);

    }

    public function user()
    {
        return $this->hasMany('App\User');
    }

}
