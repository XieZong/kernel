<?php

namespace Kernel\Providers;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function () {
            try {
                if (token()) {
                    $model = Crypt::decrypt(token());
                    $model->refresh();
                    if ($model->valid()) return $model->user;
                }
                throw new Exception();
            } catch (Exception) {
                return null;
            }
        });
    }
}
