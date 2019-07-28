<?php

namespace StudioAtual\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contacts";
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'state',
        'subject',
        'message'
    ];
}
