<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;

class AgvScope extends Model
{
	const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';

    protected $connection = 'sqlsrv2';
	
	protected $table      = 'AGV_SCOPE';
	
	protected $primaryKey = 'ID';

	protected $fillable   = [
		'AGVID'
      	,'WarehouseID'
      	// ,'Time_Created'
      	,'User_Created'
      	// ,'Time_Updated'
      	,'User_Updated'
      	,'IsDelete'
	];
}
