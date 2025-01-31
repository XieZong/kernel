<?php

namespace Kernel\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kernel\BaseModel;

/**
 * @property int|null exp_time 过期时间戳
 * @property User|null user User Model
 */
class Token extends BaseModel
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('kernel.auth_user_model'));
    }

    public function valid(): bool
    {
        if (is_null($this->exp_time) || $this->exp_time > time()) return true;
        $this->delete();
        return false;
    }
}
