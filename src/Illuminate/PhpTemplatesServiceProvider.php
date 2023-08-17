<?php

namespace PhpTemplates\Illuminate;

use Illuminate\Support\ServiceProvider;
use PhpTemplates\PhpTemplates;
use PhpTemplates\Config;
use PhpTemplates\EventDispatcher;

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
            $template = new PhpTemplates(config('phpt.src_path'), config('phpt.cache_path'), new EventDispatcher(), [
                'debug' => config('phpt.debug'),
                'aliases' => config('phpt.aliases', []),
            ]);

            return $template;
        });
        
        $this->extendViewFactory();
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
    
    private function extendViewFactory() 
    {
        $resolver = $this->app['view']->getEngineResolver();
        $finder = $this->app['view']->getFinder();

        $this->app->singleton('view', function ($app) use ($resolver, $finder) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $factory = new Factory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $factory->setContainer($app);

            $factory->share('app', $app);

            $app->terminating(static function () {
                \Illuminate\View\Component::forgetFactory();
            });

            return $factory;
        });        
    }
}
