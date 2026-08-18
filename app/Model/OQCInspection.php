<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\oqcVIR;
use App\Model\oqcVIRTS;
use App\Model\AssemblyLine;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\ProductionRuncard;
use App\Model\PackingInspector;

use App\User;

class OQCInspection extends Model
{
    protected $table = 'oqc_inspections';
    protected $connection = 'mysql_pmi_ts';
    
    // protected $table = 'tbl_package_category';
    // protected $connection = 'mysql_subsystem';
}