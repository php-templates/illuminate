<?php

namespace PhpTemplates\Illuminate;

use Illuminate\Support\ServiceProvider;
use PhpTemplates\Template;
use PhpTemplates\Config;
use PhpTemplates\EventHolder;

class PhpTemplatesServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton('phpt.view', function($app) {
            $cfg = new Config('default', config('view.paths'));
            $eventHolder = new EventHolder();
            $template = new Template(config('view.compiled'), $cfg, $eventHolder);            
            
            return new ViewFactory($template);
        });
    }    
    
    public function boot(): void
    {
        //dd($this->app['view']);
        $this->app['view']->addExtension('t.php', 'phpt', function () {
            // @codeCoverageIgnoreStart
            return $this->app->make('phpt.view');
        });
        //dd($this->app['view.finder']);
    }
}
