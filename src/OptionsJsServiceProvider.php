<?php

namespace Fahedaljghine\OptionJs;

use Illuminate\Support\ServiceProvider;

class OptionsJsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $config = __DIR__.'/../config/config.php';

        $this->publishes([
            $config => config_path('options-js.php'),
        ], 'options');
    }

    public function register()
    {
        $this->app->singleton('options.js', function() {
            $app = $this->app;

            $files = $app['files'];

            $generator = new Generators\OptionJsGenerator($files);

            return new Commands\OptionJsCommand($generator);
        });

        $this->commands('options.js');
    }

    public function provides()
    {
        return ['options.js'];
    }
}