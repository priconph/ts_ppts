<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TSPTSFinalPackingInspectionTrfficQC extends Model
{
    //
    protected $table = 'tspts_finalpackinginspection_trafficqc';
    protected $connection = 'mysql';

   public function traffic_info(){
        return $this->hasOne(User::class, 'id', 'trffic_id');
    }

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator_conformance_id');
    }
}
