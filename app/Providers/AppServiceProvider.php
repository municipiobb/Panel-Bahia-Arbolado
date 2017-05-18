<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('checkPassword', function ($attribute, $value, $parameters, $validator) {

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z._\d]{8,30}$/', $value)) {
                return false;
            }

            return true;
        });
        //Form::component('bsText', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
