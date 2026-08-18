<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\ModeOfDefect;

class DERework extends Model
{
    //
    protected $table = 'de_reworks';

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator');
    }

    public function prod_info(){
        return $this->hasOne(User::class, 'id', 'prod_id');
    }

    public function engg_info(){
        return $this->hasOne(User::class, 'id', 'engg_id');
    }

    public function qc_info(){
        return $this->hasOne(User::class, 'id', 'qc_id');
    }

    public function mode_of_defect_info(){
        return $this->hasOne(ModeOfDefect::class, 'id', 'mode_of_defect_id');
    }
}
