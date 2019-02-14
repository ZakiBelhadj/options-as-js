<?php

namespace Fahedaljghine\OptionJs\Commands;

use Config;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use FahedAljghine\OptionJs\Generators\OptionJsGenerator;

class OptionJsCommand extends Command
{
    protected $name = 'options:js';

    protected $description = 'Generate JS options file.';

    protected $generator;

    public function __construct(OptionJsGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    public function fire()
    {
        $this->handle();
    }

    public function handle()
    {
        $target = $this->argument('target');

        $settings = [
            'compress' => $this->option('compress'),
            'json' => $this->option('json'),
            'no-lib' => $this->option('no-lib'),
            'source' => $this->option('source'),
        ];

        if ($this->generator->generate($target, $settings)) {
            $this->info("Created: {$target}");

            return;
        }

        $this->error("Could not create: {$target}");
    }

    protected function getArguments()
    {
        return [
            ['target', InputArgument::OPTIONAL, 'Target path.', $this->getDefaultPath()],
        ];
    }

    protected function getDefaultPath()
    {
        return Config::get('options-js.path', public_path('options.js'));
    }

    protected function getOptions()
    {
        return [
            ['compress', 'c', InputOption::VALUE_NONE, 'Compress the JavaScript file.', null],
            ['no-lib', 'nl', InputOption::VALUE_NONE, 'Do not include the lang.js library.', null],
            ['json', 'j', InputOption::VALUE_NONE, 'Only output the messages json.', null],
            ['source', 's', InputOption::VALUE_REQUIRED, 'Specifying a custom source folder', null],
        ];
    }
}
