<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Station;

class SubStation extends Model
{
    //
    protected $table = 'sub_stations';
    protected $connection = 'mysql_pats_ts';

    public function station(){
    	return $this->belongsTo(Station::class);
    }
}
