<?php

namespace Fahedaljghine\OptionJs;

use Illuminate\Support\ServiceProvider;

class OptionsJsServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $configPath = __DIR__ . '/../config/config.php';
        $configKey = 'options-js';

        $this->publishes([
            $configPath => config_path("$configKey.php"),
        ]);

        $this->mergeConfigFrom(
            $configPath, $configKey
        );
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