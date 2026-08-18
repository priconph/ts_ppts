<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TSPTSFinalPackingInspectionQC extends Model
{
    //
    protected $table = 'tspts_finalpackinginspection_qc';
    protected $connection = 'mysql';

   public function inspector_info(){
        return $this->hasOne(User::class, 'id', 'inspector_id');
    }

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator_conformance_id');
    }
}
