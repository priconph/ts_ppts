<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TSPTSPackingConfirmation extends Model
{
    //
    protected $table = 'tspts_packingconfirmation';
    protected $connection = 'mysql';

    public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator_id');
    }
}
