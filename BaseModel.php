<?php

namespace Kernel;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use SerializeDateFormat;

    protected $guarded = ['uuid'];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = (string)Str::orderedUuid();
        });
    }
}
