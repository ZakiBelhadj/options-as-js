<?php

namespace Fahedaljghine\OptionJs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

Class Detail extends Model
{
    protected $config_key = "options_details_table_name";
    protected $default_table_name = "glb_code_detail";

    public function __construct()
    {
        parent::__construct();
        $this->connection = Config::get('options.connection', 'db_connection_name');
        $this->table = Config::get('options.' . $this->config_key, $this->default_table_name);
    }


    protected $fillable = [
        "master_id", "code", "name"
    ];


    public function master()
    {
        return $this->belongsTo(Master::class, 'master_id');
    }

}