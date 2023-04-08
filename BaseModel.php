<?php

namespace Kernel;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $guarded = ['uuid'];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (string)Str::orderedUuid();
        });
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
