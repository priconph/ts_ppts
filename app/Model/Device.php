<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MaterialProcess;

class Device extends Model
{
    //
    protected $table = 'devices';
    protected $connection = 'mysql';
    // protected $connection = 'mysql_pats_ts';

    public function material_process(){
    	return $this->hasMany(MaterialProcess::class, 'device_id', 'id');
    }
}
