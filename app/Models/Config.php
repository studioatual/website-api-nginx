<?php

namespace StudioAtual\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $fillable = [
        'company',
        'email1',
        'email2',
        'analytics',
        'title',
        'description',
        'keywords',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
