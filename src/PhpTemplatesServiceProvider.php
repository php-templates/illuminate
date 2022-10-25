<?php

namespace PhpTemplates\Illuminate;

use Illuminate\Support\ServiceProvider;

class PhpTemplatesServiceProvider extends ServiceProvider
{
    public function register()
    {dd(3);
        $this->app->singleton('view', function($app) {
            return new Template($app);
        });
    }
}
