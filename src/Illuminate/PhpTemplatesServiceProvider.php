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
        $this->mergeConfigFrom(
            __DIR__.'/../config/phpt.php', 'phpt'
        );        
        
        $this->app->singleton('phpt', function() {
            $cfg = new Config('default', config('phpt.src_path'));
            // register config aliased
            $cfg->setAlias(config('phpt.aliases', []));
            
            $eventHolder = new EventHolder();
            $template = new Template(config('phpt.cache_path'), $cfg, $eventHolder, [
                'debug' => config('phpt.debug')
            ]);
           
            return $template;
        });
    }    
    
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/phpt.php' => config_path('phpt.php'),
        ]);
        
        $this->app['view']->addExtension('t.php', 'phpt', function () {
            // @codeCoverageIgnoreStart
            $template = $this->app->make('phpt');
            $template->share($this->app['view']->getShared());
           
            return new TemplateEngine($template);
        });
    }
}
