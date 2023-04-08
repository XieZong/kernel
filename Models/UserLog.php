<?php

namespace Kernel\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLog extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function params(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
