<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Model\RapidxUser;

class YeuKitting extends Model
{
    // use HasFactory;

    protected $table = "yeu_kittings";
    protected $connection = "mysql_wbs_print";

    // public function rapidx_user_details(){
    //     return $this->hasOne(RapidxUser::class, 'id', 'created_by');
    // }
    // public function material_issuance_details(){
    // 	return $this->hasMany(MaterialIssuanceDetails::class, 'issue_no', 'issuance_no');
    // }

    public function device_info(){
    	return $this->hasOne(Device::class, 'name', 'product_name')->where('status', 1);
    }

    // public function material_issuance_info(){
    // 	return $this->hasOne(MaterialIssuance::class, 'tbl_wbs_material_kitting_id', 'id');
    // }

    public function documents_details(){
        return $this->hasMany(RapidActiveDocs::class, 'doc_title', 'item_name');
    }

    // public function kit_issuance_info(){
    //     return $this->hasOne(WBSKitIssuance::class, 'issue_no', 'issuance_no');
    // }
}
