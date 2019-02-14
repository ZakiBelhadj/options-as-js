<?php

namespace Fahedaljghine\Options;

use Illuminate\Support\ServiceProvider;

class OptionsServiceProvider extends ServiceProvider
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
        $this->app->singleton('options.js', function($app) {
            $app = $this->app;

            $files = $app['files'];

            $generator = new Generators\OptionsGenerator($files);

            return new Commands\OptionsCommand($generator);
        });

        $this->commands('options.js');
    }

    public function provides()
    {
        return ['options.js'];
    }
}