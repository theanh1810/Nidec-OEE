<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;

class AgvLog extends Model
{
    protected $connection = 'sqlsrv2';
	
	protected $table      = 'AGV_LOG';
	
	protected $primaryKey = 'ID';

	public function agv()
	{
		return $this->hasOne('App\Models\MasterData\MasterAGV', 'AGV', 'ID');
	}

	public function error()
	{
		return $this->hasOne('App\Models\ControlAGV\MasterError', 'ERROR_CODE', 'ID');
	}

	public function point()
	{
		return $this->hasOne('App\Models\ControlAGV\Point', 'POSITION', 'ID');
	}
}
