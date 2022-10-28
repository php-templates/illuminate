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
        $this->app->singleton('phpt', function() {
            $cfg = new Config('default', config('view.paths'));
            $eventHolder = new EventHolder();
            $template = new Template(config('view.compiled'), $cfg, $eventHolder);
            return $template;
        });
    }    
    
    public function boot(): void
    {
        $this->app['view']->addExtension('t.php', 'phpt', function () {
            // @codeCoverageIgnoreStart
            $template = $this->app->make('phpt');
            $template->share($this->app['view']->getShared());
            return new TemplateEngine($template);
        });
    }
}
