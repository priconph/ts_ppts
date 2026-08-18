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

class OverallInspection extends Model
{
    protected $table = 'overall_inspection';
    protected $connection = 'mysql';

    public $timestamps = false;
}
