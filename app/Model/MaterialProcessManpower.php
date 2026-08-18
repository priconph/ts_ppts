<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MaterialProcessManpower extends Model
{
    //
    protected $table = 'material_process_manpowers';
    // protected $connection = 'mysql_pats_ts';

    public function manpower_info(){
        return $this->hasOne(User::class, 'id', 'manpower_id');
    }
}
