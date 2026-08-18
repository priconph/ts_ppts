<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProdRuncardAccessory extends Model
{
    //
    protected $table = 'prod_runcard_accessories';
	// protected $connection = "mysql_pats_ts"; // commented 7/5/24
	protected $connection = "mysql";

    public function counted_by_info()
	{
    	return $this->hasOne(User::class, 'id', 'counted_by');
	}

	public function checked_by_info()
	{
    	return $this->hasOne(User::class, 'id', 'checked_by');
	}

	public function prod_supervisor_info()
	{
    	return $this->hasOne(User::class, 'id', 'prod_supervisor');
	}
}
