<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TSPTSFinalPackingInspectionTrfficQC_QC extends Model
{
    //
    protected $table = 'tspts_finalpackinginspection_trafficqc_qc';
    protected $connection = 'mysql';

   public function qc_info(){
        return $this->hasOne(User::class, 'id', 'qc_id');
    }

    public function traffic_info(){
        return $this->hasOne(User::class, 'id', 'trffic_id');
    }
}
