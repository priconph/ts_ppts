<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Model\ProductionRuncard;
use App\User;

class TSPTSOqcVir extends Model
{
    //
    // protected $table = 'tspts_oqcvir';
    protected $table = 'oqc_inspection';
    protected $connection = 'mysql';

    public function inspector_info(){
        // return $this->hasOne(User::class, 'inspector_id', 'employee_id');
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    public function lotapp_info(){
        return $this->hasOne(ProductionRuncard::class, 'id', 'prod_runcard_id');
    }
}
