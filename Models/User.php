<?php

namespace Kernel\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Kernel\BaseModel;

/**
 * @property string username 账号
 * @property array permissions 权限组
 * @property Collection roles 用户角色
 */
class User extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'permissions' => 'array'
    ];
    protected $hidden = ['password'];

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->username, admin())
        );
    }

    public function allPermissions(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->roles->pluck('permissions')->push($this->permissions)->collapse()->unique()->values()
        );
    }

    public function createToken($exp_time = null): string
    {
        return Crypt::encrypt($this->tokens()->create(['exp_time' => $exp_time]));
    }

    public function currentToken(): null|Token
    {
        if (!Auth::check()) return null;
        try {
            $model = Crypt::decrypt(token());
            return $model->refresh();
        } catch (Exception) {
            return null;
        }
    }
}
