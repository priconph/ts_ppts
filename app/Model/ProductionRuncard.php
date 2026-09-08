<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\oqcLotApp;
use App\Model\oqcVIR;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\ProductionRuncardStation;
use App\Model\ProdRuncardMaterialList;
use App\Model\MaterialProcess;
use App\Model\ProdRuncardAccessory;
use App\Model\OQCInspection_2;

use App\Model\TSPTSOqcVir;
use App\Model\TSPTSPackingConfirmation;
use App\Model\TSPTSPreliminaryPackingInspection;
use App\Model\TSPTSSupervisorValidation;
use App\Model\TSPTSFinalPackingInspection;
use App\Model\TSPTSFinalPackingInspectionQC;
use App\Model\TSPTSFinalPackingInspectionTrfficQC;
use App\Model\TSPTSFinalPackingInspectionTrfficQC_QC;
use App\Model\YeuKitting;

use App\Model\AssemblyLine;

class ProductionRuncard extends Model
{
    protected $table = 'production_runcards';
    protected $connection = 'mysql';

    public function oqc_inspection() {
        return $this->hasOne(OQCInspection_2::class, 'prod_runcard_id', 'id');
    }

    public function supervisor_prod_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_prod');
    }

    public function supervisor_qc_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_qc');
    }

    public function oqc_details(){
        return $this->hasOne(oqcLotApp::class, 'fkid_runcard', 'id')
        ->orderBy('id','DESC'); 
        // return $this->hasMany(oqcLotApp::class, 'fkid_runcard', 'id'); // updated 04/01/2026 - da - to accomodate multiple lot batch numbers for a single runcard.
    } 

    public function wbs_kitting(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }

    public function yeu_kitting(){
            // ->whereNotNull('product_name')
        return $this->hasOne(YeuKitting::class, 'po_no', 'po_no')->whereNotNull('product_name');
    }

    public function prod_runcard_material_list(){
        return $this->hasMany(ProdRuncardMaterialList::class, 'prod_runcard_id', 'id');
    }

    public function prod_runcard_station_details(){
        return $this->hasOne(ProductionRuncardStation::class, 'production_runcard_id', 'id');
    }

    public function prod_runcard_station_many_details(){
        return $this->hasMany(ProductionRuncardStation::class, 'production_runcard_id', 'id');
    }

    public function material_process_info(){
        return $this->hasOne(MaterialProcess::class, 'id', 'mat_proc_id');
    }

    public function wbs_kitting_has_many(){
        return $this->hasMany(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }

    public function eng_qualification_info(){
        return $this->hasOne(User::class, 'id', 'eng_qualification_id');
    }

    public function qc_stamp_qualification_info(){
        return $this->hasOne(User::class, 'id', 'qc_stamp_id');
    }

    public function prod_runcard_accessory_info(){
        return $this->hasMany(ProdRuncardAccessory::class, 'prod_runcard_id', 'id');
    }

    public function tspts_oqcvir_info(){
        return $this->hasMany(TSPTSOqcVir::class, 'prod_runcard_id', 'id');
    }

    public function tspts_packingconfirmation_info(){
        return $this->hasMany(TSPTSPackingConfirmation::class, 'lotapp_id', 'id');
    }

    public function tspts_packinginspection_info(){
        return $this->hasMany(TSPTSPreliminaryPackingInspection::class, 'lotapp_id', 'id');
    }

    public function tspts_supervisorvalidation_info(){
        return $this->hasMany(TSPTSSupervisorValidation::class, 'lotapp_id', 'id');
    }

    public function tspts_finalpackinginspection_info(){
        return $this->hasMany(TSPTSFinalPackingInspection::class, 'lotapp_id', 'id');
    }

    public function tspts_finalpackinginspection_info_qc(){
        return $this->hasMany(TSPTSFinalPackingInspectionQC::class, 'lotapp_id', 'id');
    }

    public function tspts_finalpackinginspection_info_traffic_qc(){
        return $this->hasMany(TSPTSFinalPackingInspectionTrfficQC::class, 'lotapp_id', 'id');
    }

    public function tspts_finalpackinginspection_info_traffic_qc_qc(){
        return $this->hasMany(TSPTSFinalPackingInspectionTrfficQC_QC::class, 'lotapp_id', 'id');
    }

    public function AssemblyLineDetails(){
        return $this->hasOne(AssemblyLine::class, 'id', 'assembly_line_id');
    }

}
