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

class OQCInspection_2_ViewChecker extends Model
{
    protected $table = 'oqc_inspection_view_scan_checker';
    protected $connection = 'mysql';

    public $timestamps = false;
}
