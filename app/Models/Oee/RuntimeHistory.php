<?php

namespace App\Models\Oee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class RuntimeHistory extends Model
{
    // use HasFactory;
    const CREATED_AT = 'Time_Created';
	const UPDATED_AT = 'Time_Updated';
	
	protected $table = 'Runtime_History';
	protected $primaryKey = 'ID';

	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}

	protected $fillable = [
	  	'Production_Log_ID',
	  	'Master_Shift_ID',
        'Master_Machine_ID',
        'IsRunning',
	  	'Master_Status_ID',
	  	'Duration',
	  	'Note',
	  	'User_Created',
	  	'Time_Created',
	  	'User_Updated',
        'Time_Updated',
        'IsDelete'
	];

    public function master_status() {
        return $this->hasOne('App\Models\MasterData\MasterStatus', 'ID', 'Master_Status_ID');
    }
}
