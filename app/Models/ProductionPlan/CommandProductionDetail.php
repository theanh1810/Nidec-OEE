<?php

namespace App\Models\ProductionPlan;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class CommandProductionDetail extends Model
{
    const CREATED_AT      = 'Time_Created';
	const UPDATED_AT      = 'Time_Updated';

	protected $table      = 'Command_Production_Detail';
	protected $primaryKey = 'ID';
	protected function serializeDate(DateTimeInterface $date)
	{
	    return $date->format('Y-m-d H:i:s');
	}
	protected $fillable   = [
		'Symbols'
	  	,'Command_ID'
		,'Mold_ID'
		,'Quantity_Mold'
		,'Materials_ID'
        ,'Product_ID'
        ,'Part_Action'
        ,'Process_ID'
        ,'Quantity'
        ,'Quantity_Production'
        ,'Quantity_Error'
		,'Date'
        ,'Time_Start'
        ,'Time_End'
        ,'Status'
        ,'Type'
		,'Version'
		,'His'
		,'Group'
	  	,'Note'
	  	,'Time_Created'
	  	,'User_Created'
	  	,'Time_Updated'
	  	,'User_Updated'
	  	,'IsDelete'
        ,'MPMT'
        ,'MaterialCode'
        ,'Cavity_Real'
	];

	public function user_created()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
	}

	public function user_updated()
	{
	    return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
	}
	public function command()
	{
	    return $this->hasOne('App\Models\ProductionPlan\CommandProduction', 'ID', 'Command_ID')->whereIsdelete(0);
	}
	public function product()
	{
	    return $this->hasOne('App\Models\MasterData\MasterProduct', 'ID', 'Product_ID')->whereIsdelete(0);
	}

	public function machine()
	{
	    return $this->hasOne('App\Models\MasterData\MasterMachine', 'ID', 'Part_Action')->whereIsdelete(0);
	}
	public function mold()
	{
	    return $this->hasOne('App\Models\MasterData\MasterMold', 'ID', 'Mold_ID')->whereIsdelete(0);
	}
}
