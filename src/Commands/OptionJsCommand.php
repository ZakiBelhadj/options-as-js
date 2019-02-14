<?php

namespace Fahedaljghine\OptionJs\Commands;

use Illuminate\Support\Facades\Config;
use FahedAljghine\OptionJs\Generators\OptionJsGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;


class OptionJsCommand extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'options:js';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate JS options file.';

    /**
     * The generator instance.
     *
     * @var OptionJsCommand
     */
    protected $generator;

    /**
     * Construct a new LangJsCommand.
     *
     * @param OptionJsGenerator $generator The generator.
     */
    public function __construct(OptionJsGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    /**
     * Fire the command. (Compatibility for < 5.0)
     */
    public function fire()
    {
        $this->handle();
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        $target = $this->argument('target');
        $options = [
            'compress' => $this->option('compress'),
            'json' => $this->option('json'),
            'no-lib' => $this->option('no-lib'),
            'source' => $this->option('source'),
        ];

        if ($this->generator->generate($target, $options)) {
            $this->info("Created: {$target}");

            return;
        }

        $this->error("Could not create: {$target}");
    }

    /**
     * Return all command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['target', InputArgument::OPTIONAL, 'Target path.', $this->getDefaultPath()],
        ];
    }

    /**
     * Return the path to use when no path is specified.
     *
     * @return string
     */
    protected function getDefaultPath()
    {
        return Config::get('options-js.path', public_path('options.js'));
    }

    /**
     * Return all command options.
     *
     * @return array
     */
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
