<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use DB;

class ApiKey extends BaseModel
{
    protected $table = 'api_auth';
    protected $fillable = ['user_id', 'key', 'description', 'expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class)
            ->withTimestamps();
    }

    public function scopeKeyExists($query, $key)
    {
        return $query->whereKey($key)->where('expires_at', '>=', DB::Raw('NOW()'))->first();
    }

}
