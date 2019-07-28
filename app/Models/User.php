<?php

namespace StudioAtual\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'hash',
        'admin',
        'access'
    ];

    protected $hidden = [
        'password',
        'hash'
    ];

    public function updateAccess()
    {
        $this->access = date('Y-m-d H:i:s');
        $this->save();
        return $this;
    }
}
