<?php

namespace App\Models;
use App\Store\SessionStore;
use Illuminate\Database\Eloquent\Model;

class Session extends AppModel
{
    protected $fillable = [
        'id',
        'customer_id',
        'token',
        'expired_at',
        'last_activity_at'
    ];

    public function createSession()
    {
        $this->id = null;

        $result = (new SessionStore())->save($this);

        return $result;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

