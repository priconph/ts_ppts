<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\ProductionRuncard;
use App\Model\DefectEscalation;
use App\Model\OqcLotappNew;

class OqcLotappRuncards extends Model
{
    protected $table = 'oqc_lotapp_runcards';
    protected $connection = 'mysql';

    public function applied_by_details()
    {
        return $this->hasOne(User::class, 'id', 'applied_by');
    }

    public function runcard_details()
    {
        return $this->hasOne(ProductionRuncard::class, 'id', 'runcard_no');
    }

    public function rework_details()
    {
        return $this->hasOne(DefectEscalation::class, 'id', 'runcard_no');
    }

    public function oqclotapp_details()
    {
        return $this->hasOne(OqcLotappNew::class, 'oqc_lotapp_id', 'oqc_lotapp_id');
    }
}
