<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use App\Models\V1\ApiKey;
use App\Models\V1\User;
use DB;
use Arr;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends BaseModel implements Authenticatable
{
    protected $table = 'users';
    protected $fillable = ['id', 'username', 'first_name', 'last_name', 'password', 'email', 'salt', 'verified', 'disabled'];
    protected $hidden = ['password', 'salt'];
    protected $appends = ['usercode', 'screenname', 'avatar'];
    protected $identifiableName = 'screenname';

    public function user()
    {
        return $this->belongsTo(User::class)
            ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    public function apiKey()
    {
        return $this->hasMany(ApiKey::class);
    }

    public function scopeKeyExists($query, $key)
    {
        return $query->whereKey($key)->where('expires_at', '>=', DB::Raw('NOW()'))->first();
    }



    public function getScreennameAttribute()
    {
        if ($this->use_nick == '-1' && !empty($this->first_name) && !empty($this->last_name)) {
            return $this->fullName;
        }

        if (!isset($this->nicks) || !count($this->nicks)) {
            return $this->username;
        }

        return Arr::get($this->nicks, $this->use_nick, $this->username);
    }

    public function getFullNameAttribute()
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }

    public function getAvatarAttribute($val, $size = 64)
    {
        if (empty($val) || $val == 'gravatar') {
            return sprintf(
                'http://www.gravatar.com/avatar/%s.png?s=%d&d=mm&rating=g',
                md5($this->attributes['email']),
                $size
            );
        }

        return $val;
    }

    public function avatar($size)
    {
        return $this->getAvatarAttribute($this->getOriginal('avatar'), $size);
    }

    public function getAuthIdentifierName() {
        return $this->screenname;
    }
    public function getAuthIdentifier() {
        return $this->id;
    }
    public function getAuthPassword() {
        return $this->password;
    }
    public function getRememberToken() {
        return null;
    }
    public function setRememberToken($value) {
        return false;
    }
    public function getRememberTokenName() {
        return null;
    }

}
