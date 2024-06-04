<?php

namespace App\Models\ProductionPlan;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CommandProduction extends Model
{
    const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';
	
	protected $table      = 'Command_Production';
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
	protected $fillable   = [
	  	'Name'
	  	,'Symbols'
        ,'Month'
        ,'Year'
	  	,'Note'
	  	,'Time_Created'
	  	,'User_Created'
	  	,'Time_Updated'
	  	,'User_Updated'
	  	,'IsDelete'
	];

	public function user_created()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
	}

	public function user_updated()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
	}

	public function detail()
	{
	    return $this->hasMany('App\Models\ProductionPlan\CommandProductionDetail', 'Command_ID', 'ID')->whereIsdelete(0);
	}
	public function running()
	{
	    return $this->hasMany('App\Models\ProductionPlan\CommandProductionDetail', 'Command_ID', 'ID')->where('Status','>',0);
	}
}
