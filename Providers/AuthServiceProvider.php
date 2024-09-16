<?php

namespace Kernel\Providers;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Kernel\Models\Token;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['auth']->viaRequest('api', function () {
            try {
                if (token()) {
                    /**
                     * @var Token $model Token Model
                     */
                    $model = Crypt::decrypt(token());
                    $model->refresh();
                    if ($model->valid()) return $model->user;
                }
                throw new Exception;
            } catch (Exception) {
                return null;
            }
        });
    }
}
