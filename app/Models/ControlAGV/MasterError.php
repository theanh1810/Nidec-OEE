<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;

class MasterError extends Model
{
	protected $connection = 'sqlsrv2';
	
	protected $table      = 'Master_Error';

	protected $primaryKey = 'ID';

	public function log()
  	{
    	return $this->hasMany('App\Models\ControlAGV\AgvLog', 'ERROR_CODE', 'ID');
  	}
}
