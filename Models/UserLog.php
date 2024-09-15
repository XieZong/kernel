<?php

namespace Kernel\Models;

use Illuminate\Database\Eloquent\Model;
use Kernel\SerializeDateFormat;

class UserLog extends Model
{
    use SerializeDateFormat;

    protected $guarded = ['id'];
    protected $casts = [
        'params' => 'array'
    ];
}
