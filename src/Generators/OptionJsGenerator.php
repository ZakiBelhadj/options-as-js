<?php

namespace Fahedaljghine\OptionJs\Generators;

use FahedAljghine\OptionJs\Models\Detail;
use Illuminate\Filesystem\Filesystem as File;
use JShrink\Minifier;
use Illuminate\Support\Facades\Config;

class OptionJsGenerator
{
    /**
     * The file service.
     *
     * @var File
     */
    protected $file;


    protected $stringsDomain = 'strings';

    /**
     * Construct a new LangJsGenerator instance.
     *
     * @param File $file The file service instance.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function generate($target, $config)
    {
        if ($config['source']) {
            $this->sourcePath = $config['source'];
        }

        $options = $this->getOptions();
        $this->prepareTarget($target);

        if ($options['no-lib']) {
            $template = $this->file->get(__DIR__ . '/Templates/options.js');
        } else if ($options['json']) {
            $template = $this->file->get(__DIR__ . '/Templates/options.json');
        } else {
            $template = $this->file->get(__DIR__ . '/Templates/optionjs_with_options.js');
            $optionjs = $this->file->get(__DIR__ . '/../../../../lib/option.min.js');
            $template = str_replace('\'{ $optionjs }\';', $optionjs, $template);
        }

        $template = str_replace('\'{ options }\'', json_encode($options), $template);

        if ($config['compress']) {
            $template = Minifier::minify($template);
        }

        return $this->file->put($target, $template);
    }


    protected function sortOptions(&$options)
    {
        if (is_array($options)) {
            ksort($options);

            foreach ($options as $key => &$value) {
                $this->sortOptions($value);
            }
        }
    }

    protected function getOptions()
    {
        $options = [];

        $types = Config::get('options.types', []);

        foreach ($types as $type) {
            if (is_int($type)) {
                $options[$type] = Detail::where('master_id', $type)
                    ->get()->pluck(['code', 'name'])->toArray();
            } else if (is_string($type)) {
                $options[$type] = Detail::whereHas('master', function($query) use ($type) {
                    return $query->where('name', $type);
                })->get()->pluck(['code', 'name'])->toArray();
            }
        }


        $models = Config::get('options.models', []);

        foreach ($models as $model) {
            $model_name = "App\\" . $model;
            if (class_exists($model_name)) {
                $options[$model] = $model_name::get();
            }
        }

        return $options;
    }

    protected function prepareTarget($target)
    {
        $dirname = dirname($target);

        if (!$this->file->exists($dirname)) {
            $this->file->makeDirectory($dirname, 0755, true);
        }
    }
}
