<?php

namespace Kernel\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kernel\BaseModel;

class Role extends BaseModel
{
    use SoftDeletes;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }
}
