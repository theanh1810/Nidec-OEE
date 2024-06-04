<?php

namespace App\Models\ControlAGV;

use Illuminate\Database\Eloquent\Model;

class AgvType extends Model
{
    protected $connection = 'sqlsrv2';
	
	protected $table      = 'AGV_Type';
	
	protected $primaryKey = 'ID';
}
