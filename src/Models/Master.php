<?php

namespace Fahedaljghine\OptionJs\Models;

use Illuminate\Database\Eloquent\Model;
use Config ;

Class Master extends Model
{
    protected $config_key = "options_master_table_name";
    protected $default_table_name = "glb_code_master";

    public function __construct()
    {
        parent::__construct();
        $this->connection = Config::get('options.connection', 'db_connection_name');
        $this->table = Config::get('options.' . $this->config_key, $this->default_table_name);
    }


    protected $fillable = [
        "name"
    ];


    public function details()
    {
        return $this->hasMany(Detail::class, 'master_id');
    }

}