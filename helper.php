<?php

use Kernel\Response;
use Illuminate\Support\Facades\Auth;

function json(bool $result = true, string $message = '', int $code = 200): Response
{
    return new Response([
        'result' => $result,
        'message' => $message ?: ($result ? '操作成功' : '操作失败'),
        'code' => $code,
    ]);
}

function token(): array|string|null
{
    return str_replace('Bearer ', '', request()->header('Authorization'));
}

function user($attribute = null, $default = null)
{
    if (!$user = Auth::user()) return null;
    if (is_null($attribute)) return $user;
    $value = $user->{$attribute};
    return is_null($value) ? value($default) : $value;
}

function admin(): array
{
    return explode('|', config('kernel.admin'));
}
