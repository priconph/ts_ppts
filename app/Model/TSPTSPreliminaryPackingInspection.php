<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TSPTSPreliminaryPackingInspection extends Model
{
    //
    protected $table = 'tspts_preliminarypackinginspection';
    protected $connection = 'mysql';

    public function inspector_info(){
        return $this->hasOne(User::class, 'id', 'inspector_id');
    }
}
