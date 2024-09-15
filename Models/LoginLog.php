<?php

namespace Kernel\Models;

use Illuminate\Database\Eloquent\Model;
use Kernel\SerializeDateFormat;

class LoginLog extends Model
{
    use SerializeDateFormat;

    protected $guarded = ['id'];
}
