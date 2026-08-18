<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\DEStations;
use App\Model\DEMaterials;
use App\Model\DERework;
use App\Model\SubStation;

class DefectEscalation extends Model
{
    //

    protected $table = 'defect_escalations';
    protected $connection = 'mysql';

    public function defect_escalation_station_many_details(){
        return $this->hasMany(DEStations::class, 'defect_escalation_id', 'id');
    }

    public function rework_station_many_details()
    {
        return $this->hasMany(DERework::class, 'defect_escalation_id', 'id');
    }


    public function defect_escalation_material_list(){
        return $this->hasMany(DEMaterials::class, 'defect_escalation_id', 'id');
    }

    public function supervisor_prod_info(){
        return $this->hasOne(User::class, 'id', 'production');
    }

    public function supervisor_qc_info(){
        return $this->hasOne(User::class, 'id', 'lqc');
    }

    public function sub_station_info()
    {
        return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }
}
