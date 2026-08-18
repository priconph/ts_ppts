<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Machine;

class PackingListDetails extends Model
{
    //
    protected $table = 'tbl_packing_list_details';
    protected $connection = 'packing_list';
}
