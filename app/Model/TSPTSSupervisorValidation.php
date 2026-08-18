<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TSPTSSupervisorValidation extends Model
{
    //
    protected $table = 'tspts_supervisorvalidation';
    protected $connection = 'mysql';

     public function supervisor_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_id');
    }
}
