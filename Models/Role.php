<?php

namespace Kernel\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kernel\BaseModel;

class Role extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'permissions' => 'array'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
