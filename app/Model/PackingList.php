<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Machine;

class PackingList extends Model
{
    //
    protected $table = 'tbl_packing_list';
    protected $connection = 'packing_list';
}
