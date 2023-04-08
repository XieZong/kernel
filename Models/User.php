<?php

namespace Kernel\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Kernel\BaseModel;

class User extends BaseModel
{
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'password'];

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
            get: fn() => in_array($this->username, explode('|', config('kernel.admin')))
        );
    }

    public function permissions(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
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

    public function currentToken()
    {
        if (!Auth::check()) return null;
        $model = Crypt::decrypt(token());
        return $model->refresh();
    }
}
